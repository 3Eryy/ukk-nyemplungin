<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rentals;
use App\Models\Returns;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Menampilkan daftar data pengembalian.
     */
    public function index(Request $request)
    {
        $query = Returns::with(['rental', 'rental.user', 'rental.items']);

        // Fitur Pencarian Sederhana (Opsional, sesuai UI)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('rental.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // $query->orderBy('return_date', 'desc'); 
        if ($request->filled('status_late')) {
            $returns = $query->get(); // Ambil semua data dulu untuk filter manual
            $filteredReturns = $returns->filter(function ($return) use ($request) {
                $isLate = $return->isLate();
                if ($request->status_late == 'tepat_waktu') {
                    return !$isLate;
                } elseif ($request->status_late == 'terlambat') {
                    return $isLate;
                }
                return true;
            });

            // Manual pagination untuk hasil filter
            $perPage = 10;
            $currentPage = $request->get('page', 1);
            $currentItems = $filteredReturns->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $returns = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $filteredReturns->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Jika tidak ada filter status, gunakan pagination biasa
            $returns = $query->orderBy('return_date', 'desc')->paginate(10);
        }

        // Pertahankan query string saat pagination
        if (!isset($returns->additional)) {
            $returns->appends($request->all());
        }

        // Hitung statistik untuk kartu informasi
        $allReturns = Returns::with('rental')->get();
        $stats = [
            'total' => $allReturns->count(),
            'tepat_waktu' => $allReturns->filter(function ($return) {
                return !$return->isLate();
            })->count(),
            'terlambat' => $allReturns->filter(function ($return) {
                return $return->isLate();
            })->count(),
            'total_denda' => $allReturns->sum(function ($return) {
                return $return->calculateFine();
            }),
            'kondisi_baik' => $allReturns->where('condition', 'baik')->count(),
            'kondisi_rusak' => $allReturns->where('condition', 'rusak')->count(),
            'kondisi_hilang' => $allReturns->where('condition', 'hilang')->count(),
        ];

        $availableRentals = Rentals::with('user')
            ->whereIn('status', ['disetujui'])
            ->whereDoesntHave('return') // rental yang belum punya return
            ->get();

        // 4. BARU setelah semua filter, lakukan paginate()
        $returns = $query->paginate(10);

        return view('admin.return.index', compact('returns', 'stats', 'availableRentals'));
    }

    // insert data pengembalian
    public function store(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'return_date' => 'required|date',
            'condition' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $exitingReturn = Returns::where('rental_id', $request->rental_id)->first();
        if ($exitingReturn) {
            return redirect()->back()->with('error', 'Data pengembalian untuk rental ini sudah ada.');
        }

        $rental = Rentals::findOrFail($request->rental_id);

        $returnDate = Carbon::parse($request->return_date);
        $rentalEnd = Carbon::parse($rental->rental_end);
        $daysLate = $rentalEnd->diffInDays($returnDate, false);
        $fineAmount = $daysLate > 0 ? $daysLate * 1000 : 0;

        $return = Returns::create([
            'rental_id' => $request->rental_id,
            'return_date' => $request->return_date,
            'condition' => $request->condition,
            'fine_amount' => 0, // Bisa diisi 0 atau sesuai kebijakan
            'notes' => $request->notes,
            'handled_by' => auth()->id()
        ]);

        // Update status rental menjadi selesai
        $rental->update(['status' => 'selesai']);

        $message = 'Data pengembalian berhasil ditambahkan.';
        if ($daysLate > 0) {
            $message .= ' Terlambat ' . $daysLate . ' hari dengan denda Rp ' . number_format($fineAmount, 0, ',', '.');
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $return
        ]);
    }

    // Method lain (create, store, edit, delete) bisa ditambahkan di sini
    public function destroy($id)
    {
        $return = Returns::findOrFail($id);
        $return->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function exportPdf()
    {
        $returns = Returns::with(['rental', 'rental.user', 'rental.items'])->get();

        $startNumber = 2;

        $pdf = Pdf::loadView('admin.return.export-pdf', compact('returns', 'startNumber'));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('data-pengembalian-' . date('Y-m-d') . '.pdf');
    }
}
