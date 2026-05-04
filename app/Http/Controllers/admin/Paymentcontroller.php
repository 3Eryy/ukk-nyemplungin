<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Paymentcontroller extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        // 1. Statistik ringkasan
        $summary = $this->getSummaryStats($startDateCarbon, $endDateCarbon);

        // Grafik pendapatan harian
        $dailyRevenue = $this->getDailyRevenue($startDateCarbon, $endDateCarbon);

        // 3. Pendapatan Per metode pembayaran
        $paymentMethodStats = $this->getPaymentMethodstats($startDateCarbon, $endDateCarbon);

        // 4. Statistik status pembayaran
        $paymentStatusStats = $this->getPaymentStatusStats($startDateCarbon, $endDateCarbon);

        // 5. Top Transactions
        $topTransactions = $this->getTopTransactions($startDateCarbon, $endDateCarbon);

        // 6. Pendapatan Bulanan (untuk grafik tahunan)
        $monthlyRevenue = $this->getMonthlyRevenue($request->get('year', Carbon::now()->year));

        // 7. Rata-rata per Transaksi
        $avgTransaction = $this->getAverageTransaction($startDateCarbon, $endDateCarbon);

        return view('admin.finance.index', compact(
            'startDate',
            'endDate',
            'summary',
            'dailyRevenue',
            'paymentMethodStats',
            'paymentStatusStats',
            'topTransactions',
            'monthlyRevenue',
            'avgTransaction'
        ));
    }

    private function getSummaryStats($startDate, $endDate)
    {
        $payments = Payments::whereBetween('payment_date', [$startDate, $endDate])
            ->where('payment_status', 'paid');

        return [
            'total_pendapatan' => $payments->sum('amount'),
            'total_transaksi' => $payments->count(),
            'total_transfer' => (clone $payments)->where('payment_method', 'transfer')->count(),
            'total_cash' => (clone $payments)->where('payment_method', 'cash')->count(),
            'pendapatan_transfer' => (clone $payments)->where('payment_method', 'transfer')->sum('amount'),
            'pendapatan_cash' => (clone $payments)->where('payment_method', 'cash')->sum('amount'),
            'transaksi_pending' => Payments::whereBetween('payment_date', [$startDate, $endDate])
                ->where('payment_status', 'pending')->count(),
            'transaksi_failed' => Payments::whereBetween('payment_date', [$startDate, $endDate])
                ->where('payment_status', 'failed')->count(),
        ];
    }

    private function getDailyRevenue($startDate, $endDate)
    {
        return Payments::whereBetween('payment_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPaymentMethodStats($startDate, $endDate)
    {
        return Payments::whereBetween('payment_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('payment_method')
            ->get();
    }

    private function getPaymentStatusStats($startDate, $endDate)
    {
        return Payments::whereBetween('payment_date', [$startDate, $endDate])
            ->select(
                'payment_status',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('payment_status')
            ->get();
    }

    private function getTopTransactions($startDate, $endDate, $limit = 10)
    {
        return Payments::with('rental.user')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->orderBy('amount', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getMonthlyRevenue($year)
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            
            $revenue = Payments::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                ->where('payment_status', 'paid')
                ->sum('amount');
                
            $monthlyData[] = [
                'month' => $startOfMonth->format('F'),
                'month_num' => $month,
                'revenue' => $revenue,
                'transaction_count' => Payments::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                    ->where('payment_status', 'paid')
                    ->count()
            ];
        }
        
        return $monthlyData;
    }

    private function getAverageTransaction($startDate, $endDate)
    {
        $avg = Payments::whereBetween('payment_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->avg('amount');
            
        return $avg ?? 0;
    }

    public function getChartData(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $monthlyData = $this->getMonthlyRevenue($year);

        return response()->json([
            'labels' => collect($monthlyData)->pluck('month'),
            'revenue' => collect($monthlyData)->pluck('revenue'),
            'transactions' => collect($monthlyData)->pluck('transaction_count'),
        ]);
    }
}