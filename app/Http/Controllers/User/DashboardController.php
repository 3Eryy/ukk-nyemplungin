<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Equipments;
use App\Models\Rentals;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // statistik untuk dashboard user
        $totalRentals = Rentals::where('user_id', $user->id)->count();
        $activeRentals = Rentals::where('user_id', $user->id)
                               ->whereIn('status', ['menunggu', 'disetujui'])
                               ->count();
        $completedRentals = Rentals::where('user_id', $user->id)
                                  ->where('status', 'selesai')
                                  ->count();
        $cartCount = Cart::where('user_id', $user->id)->count();

        // Rental terbaru
        $recentRentals = Rentals::with(['items.equipment'])
                               ->where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();
        
        // Equipment yang tersedia (untuk rekomendasi)
        $recommendedEquipments = Equipments::where('available_status', 'tersedia')
                                          ->where('condition_status', 'baik')
                                          ->with('category')
                                          ->inRandomOrder()
                                          ->take(6)
                                          ->get();
        
        return view('user.dashboard.index', compact(
            'user',
            'totalRentals',
            'activeRentals',
            'completedRentals',
            'cartCount',
            'recentRentals',
            'recommendedEquipments'
        ));
    }

}
