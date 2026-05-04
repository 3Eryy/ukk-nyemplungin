<?php
// app/Http/Controllers/User/ActivityLogController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Rentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->where('user_id', auth()->id())
            ->latest();

        // Filter berdasarkan aksi
        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $activities = $query->paginate(15)->withQueryString();
        
        // Statistik aktivitas
        $stats = [
            'total' => ActivityLog::where('user_id', auth()->id())->count(),
            'this_month' => ActivityLog::where('user_id', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->count(),
            'today' => ActivityLog::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count(),
        ];

        // Data untuk filter
        $actions = ActivityLog::where('user_id', auth()->id())
            ->select('action')
            ->distinct()
            ->pluck('action');

        return view('user.activity-logs.index', compact('activities', 'stats', 'actions'));
    }

    public function show($id)
    {
        $activity = ActivityLog::with('user')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Ambil data terkait jika ada
        $relatedData = null;
        if ($activity->model_type && $activity->model_id) {
            try {
                $model = app($activity->model_type);
                $relatedData = $model->find($activity->model_id);
            } catch (\Exception $e) {
                $relatedData = null;
            }
        }

        return view('user.activity-logs.show', compact('activity', 'relatedData'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user')
            ->where('user_id', auth()->id());

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->get();

        // Generate CSV
        $fileName = 'aktivitas_saya_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Tanggal & Waktu', 'Aksi', 'Deskripsi', 'IP Address', 'User Agent']);

            foreach ($activities as $index => $activity) {
                fputcsv($file, [
                    $index + 1,
                    $activity->created_at->format('d/m/Y H:i:s'),
                    $activity->action,
                    $activity->description,
                    $activity->ip_address,
                    $activity->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $activity = ActivityLog::where('user_id', auth()->id())->findOrFail($id);
        $activity->delete();

        return redirect()->route('user.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil dihapus');
    }

    public function clearAll()
    {
        ActivityLog::where('user_id', auth()->id())->delete();

        return redirect()->route('user.activity-logs.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus');
    }
}