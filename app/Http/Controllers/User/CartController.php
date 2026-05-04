<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Equipments;
use App\Models\RentalItems;
use App\Models\Rentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('equipment.category')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->equipment->hourly_price * $item->quantity;
        });

        return view('user.cart.index', compact('cartItems', 'total'));
    }

    public function add($equipmentId)
    {
        $equipment = Equipments::findOrFail($equipmentId);

        // Cek ketersediaan
        if ($equipment->available_status != 'tersedia' || $equipment->condition_status != 'baik') {
            return redirect()->back()->with('error', 'Barang tidak tersedia untuk disewa');
        }

        // Cek apakah sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('equipment_id', $equipmentId)
            ->first();

        if ($existingCart) {
            // Update quantity jika sudah ada
            $existingCart->quantity += 1;
            $existingCart->save();
        } else {
            // Tambah baru
            Cart::create([
                'user_id' => Auth::id(),
                'equipment_id' => $equipmentId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('user.cart.index')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function remove($cartId)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $cart->delete();

        return redirect()->route('user.cart.index')->with('success', 'Barang dihapus dari keranjang');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.cart.index')->with('success', 'Keranjang berhasil dikosongkan');
    }

    // CartController.php - method checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'rental_start'   => 'required|date|after_or_equal:now',
            'rental_end'     => 'required|date|after:rental_start',
            'payment_method' => 'required|in:transfer,cod',
        ]);

        $cartItems = Cart::with('equipment')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }

        DB::beginTransaction();

        try {
            $start = \Carbon\Carbon::parse($request->rental_start);
            $end   = \Carbon\Carbon::parse($request->rental_end);
            $days  = $start->diffInDays($end);

            if ($days == 0) {
                $days = 1;
            }

            // Buat rental
            $rental = Rentals::create([
                'user_id'        => Auth::id(),
                'rental_start'   => $request->rental_start,
                'rental_end'     => $request->rental_end,
                'total_price'    => 0,
                'payment_method' => $request->payment_method, // 'transfer' atau 'cod'
                'status'         => 'menunggu',
                'approved_by'    => null,
            ]);

            $totalPrice = 0;

            foreach ($cartItems as $item) {
                $equipment = $item->equipment;

                if ($equipment->stock < $item->quantity) {
                    throw new \Exception("Stok {$equipment->name} tidak cukup. Tersedia: {$equipment->stock}");
                }
                $itemTotalPrice = $item->equipment->hourly_price * $item->quantity * $days;

                RentalItems::create([
                    'rental_id'    => $rental->id,
                    'equipment_id' => $item->equipment_id,
                    'quantity'     => $item->quantity,
                    'price'        => $item->equipment->hourly_price,
                    'subtotal'     => $itemTotalPrice,
                    'rental_start' => $request->rental_start,
                    'rental_end'   => $request->rental_end,
                ]);
                $equipment->decrement('stock', $item->quantity);

                if ($equipment->stock <= 0) {
                    $equipment->update(['available_status' => 'tidak tersedia']);
                }

                $totalPrice += $itemTotalPrice;
            }

            $rental->update(['total_price' => $totalPrice]);

            // Hapus keranjang
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // ── Percabangan metode pembayaran ──
            if ($request->payment_method === 'cod') {
                return redirect()->route('user.rentals.receipt', $rental->id)
                    ->with('success', 'Pesanan berhasil! Silakan bayar saat pengambilan.');
            }

            // Transfer → ke halaman pembayaran
            return redirect()->route('payments.index', $rental->id)
                ->with('success', 'Silakan lanjutkan ke pembayaran');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function receipt($rentalId)
    {
        $rental = Rentals::with(['user', 'rentalItems.equipment.category'])
            ->where('user_id', Auth::id())
            ->findOrFail($rentalId);

        // Cek apakah request dari print
        if (request()->has('print')) {
            return view('user.rentals.receipt-print', compact('rental'));
        }

        return view('user.rentals.receipt', compact('rental'));
    }

    public function downloadReceipt($rentalId)
    {
        $rental = Rentals::with(['user', 'rentalItems.equipment.category'])
            ->where('user_id', Auth::id())
            ->findOrFail($rentalId);

        // Gunakan view khusus untuk PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.rentals.receipt-print', compact('rental'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('nota-penyewaan-' . $rental->id . '.pdf');
    }
}
