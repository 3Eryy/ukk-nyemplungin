<?php

namespace App\Services;

use App\Models\Rentals;
use App\Models\Payments;  // Ganti: Payment (tanpa s) lebih standar
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        \Log::debug('Midtrans config', [
            'server_key'   => Config::$serverKey,
            'is_production' => Config::$isProduction,
        ]);

        // HAPUS ini untuk production, atau atur berdasarkan environment
        if (!Config::$isProduction) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }

    public function createTransaction(Rentals $rental)
    {
        // Load relasi yang diperlukan
        $rental->load('rentalItems.equipment', 'user');

        // debug
        \Log::debug('=== DEBUG RENTAL DATA ===', [
            'rental_id'    => $rental->id,
            'user'         => $rental->user?->toArray(),
            'rental_items' => $rental->rentalItems->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'equipment_id' => $item->equipment_id,
                    'equipment'    => $item->equipment?->toArray(),
                    'price'        => $item->price,
                    'price_type'   => gettype($item->price),
                    'quantity'     => $item->quantity,
                ];
            })->toArray(),
        ]);

        // Mapping item_details
        $items = $rental->rentalItems
            ->filter(fn($item) => $item->equipment !== null)
            ->map(function ($item) {
                $price = (int) round($item->price);
                $qty   = max(1, (int) $item->quantity);

                return [
                    'id'       => (string) $item->equipment_id,
                    'price'    => $price,
                    'quantity' => $qty,
                    'name'     => substr($item->equipment->name ?? 'Item Rental', 0, 50),
                ];
            })->values();

        // Guard: jangan lanjut kalau items kosong
        if ($items->isEmpty()) {
            throw new \Exception('Tidak ada item valid untuk transaksi. Pastikan semua item rental memiliki data equipment.');
        }

        // Hitung total dari items (bukan dari rental->total_price)
        $total = (int) $items->sum(fn($item) => $item['price'] * $item['quantity']);

        if ($total <= 0) {
            throw new \Exception('Total transaksi tidak valid (0 atau negatif).');
        }

        // Verifikasi balance sebelum kirim ke Midtrans
        $itemTotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

        \Log::debug('Midtrans balance check', [
            'gross_amount' => $total,
            'item_total'   => $itemTotal,
            'match'        => $total === $itemTotal,
            'items'        => $items->toArray(),
        ]);

        // Buat record payment
        $payment = Payments::create([
            'rental_id' => $rental->id,
            'order_id'  => Payments::generateOrderId($rental->id),
            'amount'    => $total,
            'status'    => 'pending',
        ]);

        \Log::info('Creating Midtrans transaction', [
            'order_id'    => $payment->order_id,
            'total'       => $total,
            'items_count' => $items->count(),
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $payment->order_id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $rental->user->name  ?? 'Customer',
                'email'      => $rental->user->email ?? 'customer@example.com',
                'phone'      => $rental->user->phone ?? '081234567890',
            ],
            'item_details' => $items->toArray(),
        ];

        try {
            \Log::debug('Midtrans params:', $params);

            $snapToken = Snap::getSnapToken($params);

            return [
                'snap_token' => $snapToken,
                'order_id'   => $payment->order_id,
                'payment_id' => $payment->id,
            ];
        } catch (\Exception $e) {
            // Hapus payment record yang sudah terlanjur dibuat
            $payment->delete();

            \Log::error('Midtrans create transaction error:', [
                'message'      => $e->getMessage(),
                'gross_amount' => $total,
                'item_total'   => $itemTotal,
                'items'        => $items->toArray(),
                'trace'        => $e->getTraceAsString(),
            ]);

            throw new \Exception('Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function handleNotification($notificationData)
    {
        // Convert ke object jika masih array
        $data = is_array($notificationData) ? (object) $notificationData : $notificationData;

        // 🔥 PERBAIKAN: Cek apakah properti yang diperlukan ada
        if (!isset($data->order_id) || !isset($data->status_code) || !isset($data->gross_amount) || !isset($data->signature_key)) {
            \Log::warning('Invalid notification data', ['data' => $data]);
            throw new \Exception('Invalid notification data');
        }

        $signature = hash(
            'sha512',
            $data->order_id .
                $data->status_code .
                $data->gross_amount .
                Config::$serverKey
        );

        if ($signature !== $data->signature_key) {
            \Log::warning('Invalid signature', [
                'calculated' => $signature,
                'received' => $data->signature_key
            ]);
            throw new \Exception('Invalid signature');
        }

        $payment = Payments::where('order_id', $data->order_id)->first();

        if (!$payment) {
            \Log::warning('Payment not found', ['order_id' => $data->order_id]);
            throw new \Exception('Payment not found');
        }

        // Update payment status
        $payment->update([
            'status' => $data->transaction_status,
            'payment_type' => $data->payment_type ?? null,
            'midtrans_response' => json_decode(json_encode($data), true)
        ]);

        // Update VA number jika ada
        if (isset($data->va_numbers) && count($data->va_numbers) > 0) {
            $payment->update([
                'bank' => $data->va_numbers[0]->bank,
                'va_number' => $data->va_numbers[0]->va_number,
            ]);
        }

        // Handle permata VA (format berbeda)
        if (isset($data->permata_va_number)) {
            $payment->update([
                'bank' => 'permata',
                'va_number' => $data->permata_va_number,
            ]);
        }

        // Update status rental jika payment success
        if (in_array($data->transaction_status, ['settlement', 'capture'])) {
            $payment->update(['paid_at' => now()]);

            // Pastikan relasi rental ada
            if ($payment->rental) {
                $payment->rental->update(['status' => 'paid']);
            }
        }

        \Log::info('Payment notification processed', [
            'payment_id' => $payment->id,
            'status' => $data->transaction_status,
            'order_id' => $data->order_id
        ]);

        return $payment;
    }

    public function checkStatus($orderId)
    {
        try {
            $status = MidtransTransaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            \Log::error('Check status error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Gagal cek status transaksi: ' . $e->getMessage());
        }
    }
}
