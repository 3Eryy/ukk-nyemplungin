<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // show page
    public function index()
    {
        // CARD
        // total alat
        $totalEquipments = DB::table('equipments')->count();

        // total rental items
        $totalRentals = DB::table('rentals')->where('status', 'disetujui')->count();

        // total item tersedia
        $totalReadeyItems = DB::table('equipments')->where('available_status', 'tersedia')->count();

        // total item dipinjam
        $totalBorrowItems = DB::table('equipments')->where('available_status', 'dipinjam')->count();

        // GRAFIK
        $year = now()->year;

        $peminjamanPerBulan = DB::table('rentals')
            ->select(DB::raw('MONTH(rental_start) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('rental_start', $year)
            ->groupBy(DB::raw('MONTH(rental_start)'))
            ->orderBy(DB::raw('MONTH(rental_start)'))
            ->get();

        $dataGrafik = array_fill(1, 12, 0);

        foreach ($peminjamanPerBulan as $data) {
            $dataGrafik[$data->month] = $data->total;
        }

        // AKTIVITAS TERBARU
        $aktivitasTerbaru = DB::table('rentals')
            ->join('users', 'rentals.user_id', '=', 'users.id')
            ->join('rental_items', 'rentals.id', '=', 'rental_items.rental_id')
            ->join('equipments', 'rental_items.equipment_id', '=', 'equipments.id')
            ->select(
                'rentals.status',
                'rentals.created_at',
                'users.name as user_name',
                'equipments.name as equipment_name'
            )
            ->orderBy('rentals.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->created_at = Carbon::parse($item->created_at);
                return $item;
            });

        // total 
        return view('admin.dashboard.index', [
            'totalEquipments' => $totalEquipments,
            'totalRentals' => $totalRentals,
            'totalReadeyItems' => $totalReadeyItems,
            'totalBorrowItems' => $totalBorrowItems,
            'dataGrafik' => array_values($dataGrafik),
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'year' => $year
        ]);
    }
}
