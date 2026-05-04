<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategories;
use App\Models\Rentals;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\RentalItems;
use App\Models\Equipments;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rentals::with(['rentalItems.equipment'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $categories = EquipmentCategories::with('equipments')
            ->whereHas('equipments', function ($query) {
                $query->where('available_status', 'tersedia')
                    ->where('condition_status', 'baik');
            })
            ->get();

        return view('user.rentals.create', compact('categories'));
    }

    // RentalController.php - method show
    public function show($id)
    {
        $rental = Rentals::with(['rentalItems.equipment', 'payments' => function ($query) {
            $query->latest();
        }])->where('user_id', auth()->id())->findOrFail($id);

        // Perbaiki: ambil payment terbaru, bukan firstOrFail
        $payment = $rental->payments->first(); // Bisa null jika belum ada pembayaran

        return view('user.rentals.show', compact('rental', 'payment'));
    }

    public function cancel($id)
    {
        $rental = Rentals::where('user_id', Auth::id())
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $rental->update(['status' => 'dibatalkan']);

        return redirect()->route('user.rentals.show', $id)
            ->with('success', 'Peminjaman berhasil dibatalkan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after:rental_start',
            'notes' => 'nullable|string'
        ]);

        $userId = auth()->id();

        $carts = Cart::with('equipment')->where('user_id', auth()->id())->get();

        foreach ($carts as $cart) {
            if ($cart->equipment->stock < $cart->quantity) {
                return back()->with('error', "Stok {$cart->equipment->name} tidak cukup. Tersedia: {$cart->equipment->stock}");
            }

            if ($cart->equipment->available_status !== 'tersedia' || $cart->equipment->condition_status !== 'baik') {
                return back()->with('error', "{$cart->equipment->name} sedang tidak tersedia untuk disewa");
            }
        }

        DB::transaction(function () use ($carts, $userId, $request) {

            $rental = Rentals::create([
                'user_id' => $userId,
                'rental_start' => $request->rental_start,
                'rental_end' => $request->rental_end,
                'total_price' => 0,
                'status' => 'menunggu',
                'approved_by' => null,
                'notes' => $request->notes,
            ]);

            $totalPrice = 0;

            foreach ($carts as $cart) {
                $equipment = $cart->equipment;

                if ($equipment->stock < $cart->quantity) {
                    throw new \Exception("Stok {$equipment->name} tidak cukup. Tersedia: {$equipment->stock}");
                }

                $start = new \DateTime($request->rental_start);
                $end = new \DateTime($request->rental_end);
                $days = $start->diff($end)->days;

                if ($days == 0) {
                    $days = 1;
                }

                $pricePerDay = $equipment->hourly_price;
                $itemTotalPrice = $pricePerDay * $cart->quantity * $days;

                RentalItems::create([
                    'rental_id' => $rental->id,
                    'equipment_id' => $equipment->id,
                    'quantity' => $cart->quantity,
                    'price' => $pricePerDay, // Isi price
                    'subtotal' => $itemTotalPrice, // Isi subtotal
                    'rental_start' => $request->rental_start, // Isi rental_start
                    'rental_end' => $request->rental_end, // Isi rental_end
                ]);

                $equipment->decrement('stock', $cart->quantity);

                if ($equipment->stock <= 0) {
                    $equipment->update([
                        'available_status' => 'tidak tersedia'
                    ]);
                }

                $totalPrice += $itemTotalPrice;
            }

            $rental->update([
                'total_price' => $totalPrice
            ]);

            Cart::where('user_id', $userId)->delete();
        });

        return redirect()->route('user.rentals.index')
            ->with('success', 'Peminjaman berhasil dibuat');
    }
}
