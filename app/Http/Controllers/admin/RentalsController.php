<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
// use App\Models\Payments;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Rentals;
use Illuminate\Http\Request;

class RentalsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Statistik untuk Kartu Atas
        $stats = [
            'selesai'  => Rentals::where('status', 'selesai')->count(),
            'ditolak'  => Rentals::where('status', 'ditolak')->count(),
            'menunggu' => Rentals::where('status', 'menunggu')->count(),
            'dipinjam' => Rentals::where('status', 'dipinjam')->count(),
        ];

        // 2. Query Data Tabel dengan Pencarian & Pagination
        $rentals = Rentals::with([
            'user',
            'rentalItems.equipment',
            'payments'
        ]);

        if ($request->has('search')) {
            $rentals->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // filter tanggal
        if ($request->filled('tanggal')) {
            $rentals->whereDate('created_at', $request->tanggal);
        }
        
        $rentals = $rentals->latest()->paginate(10);

        $rentals->appends($request->all());

        return view('admin.rental.index', compact('rentals', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,selesai,ditolak',
        ]);

        $rental = Rentals::findOrFail($id);
        $rental->status = $request->status;

        // Opsional: Isi approved_by jika status berubah jadi active
        if ($request->status == 'dipinjam' && !$rental->approved_by) {
            $rental->approved_by = auth()->id();
        }

        $rental->save();

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rental = Rentals::findOrFail($id);
        $rental->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // function export pdf
    public function exportPdf(Request $request)
    {
        $rentals = Rentals::with([
            'user',
            'rentalItems.equipment',
            'payments' // tambahkan ini
        ])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.rental.export-pdf', compact('rentals'));

        return $pdf->stream('data-rental.pdf');
    }
}
