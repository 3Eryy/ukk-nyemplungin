<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use App\Models\Rentals;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // PaymentController.php - method index
    public function index(Rentals $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek apakah rental sudah dibayar
        if ($rental->isPaid()) {
            return redirect()->route('user.rentals.show', $rental->id)
                ->with('info', 'Pembayaran sudah berhasil dilakukan');
        }

        // Perbaiki: payments (plural)
        $payment = $rental->payments()->latest()->first();

        return view('user.payments.index', compact('rental', 'payment'));
    }

    // app/Http/Controllers/User/PaymentController.php
    public function create(Rentals $rental)
    {
        \Log::info('=== PAYMENT CREATE METHOD CALLED ===', [
            'rental_id' => $rental->id,
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ajax' => request()->ajax(),
            'headers' => request()->headers->all()
        ]);

        if ($rental->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $rental->load('rentalItems.equipment');

            $result = $this->midtrans->createTransaction($rental);

            // Tambahkan logging untuk debug
            \Log::info('Midtrans transaction created:', $result);

            return response()->json([
                'snap_token' => $result['snap_token'],
                'order_id' => $result['order_id']
            ]);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Midtrans error: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            $payment = $this->midtrans->handleNotification($request->all());

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payments::where('order_id', $orderId)->firstOrFail();

        return redirect()->route('user.rentals.show', $payment->rental_id)
            ->with('success', 'Pembayaran sedang diproses');
    }

    /**
     * Unfinish page (pembayaran gagal/batal)
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('user.rentals.index')
            ->with('error', 'Pembayaran dibatalkan');
    }

    /**
     * Error page
     */
    public function error(Request $request)
    {
        return redirect()->route('user.rentals.index')
            ->with('error', 'Terjadi kesalahan dalam pembayaran');
    }
}
