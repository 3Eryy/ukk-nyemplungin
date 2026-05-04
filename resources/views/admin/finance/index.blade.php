@extends('layouts.admin.index')

@section('content')
    <div class="container-fluid px-4 py-4">
        {{-- Header --}}
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Analisa Keuangan</h1>
            <p>ini adalah halaman analisa keuangan</p>
        </div>
        {{-- Filter Periode --}}
        <div class="mb-6">
            <form method="GET" class="flex gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="self-center">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-[#000DFB] text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Pendapatan</p>
                        <p class="text-xl font-bold text-gray-700">Rp
                            {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Transaksi Pinjam</p>
                        <p class="text-xl font-bold text-gray-700">{{ $summary['total_transaksi'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Rata-rata Transaksi</p>
                        <p class="text-xl font-bold text-gray-700">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Transaksi Pending</p>
                        <p class="text-xl font-bold text-black-600">{{ $summary['transaksi_pending'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Grafik Pendapatan Harian --}}
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Pendapatan Harian</h3>
                <canvas id="dailyRevenueChart" height="200"></canvas>
            </div>

            {{-- Grafik Pendapatan Bulanan --}}
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Pendapatan Bulanan {{ request('year', date('Y')) }}</h3>
                    <select id="yearSelect" class="px-3 py-1 border rounded-lg">
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <canvas id="monthlyRevenueChart" height="200"></canvas>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Statistik Metode Pembayaran --}}
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Metode Pembayaran</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($paymentMethodStats as $stat)
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">{{ ucfirst($stat->payment_method) }}</p>
                            <p class="text-lg font-bold text-blue-600">{{ $stat->total }} transaksi</p>
                            <p class="text-sm text-gray-600">Rp {{ number_format($stat->total_amount, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <canvas id="paymentMethodChart" height="150" class="mt-4"></canvas>
            </div>

            {{-- Statistik Status Pembayaran --}}
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Status Pembayaran</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($paymentStatusStats as $stat)
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <span
                                class="inline-block w-2 h-2 rounded-full 
                        {{ $stat->payment_status == 'paid' ? 'bg-green-500' : ($stat->payment_status == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ ucfirst($stat->payment_status) }}</p>
                            <p class="text-sm font-bold">{{ $stat->total }}</p>
                        </div>
                    @endforeach
                </div>
                <canvas id="paymentStatusChart" height="150" class="mt-4"></canvas>
            </div>
        </div>

        {{-- Top Transactions --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-4">Transaksi Terbesar</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($topTransactions as $index => $transaction)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $transaction->rental->user->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $transaction->payment_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                {{ $transaction->payment_method == 'transfer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 font-semibold">Rp
                                    {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Data untuk Chart
            const dailyData = @json($dailyRevenue);
            const monthlyData = @json($monthlyRevenue);
            const paymentMethodStats = @json($paymentMethodStats);
            const paymentStatusStats = @json($paymentStatusStats);

            // Chart Pendapatan Harian
            new Chart(document.getElementById('dailyRevenueChart'), {
                type: 'line',
                data: {
                    labels: dailyData.map(d => d.date),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: dailyData.map(d => d.total_revenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + value.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });

            // Chart Pendapatan Bulanan
            new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: monthlyData.map(d => d.month.substring(0, 3)),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: monthlyData.map(d => d.revenue),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + value.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });

            // Chart Metode Pembayaran (Pie)
            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'pie',
                data: {
                    labels: paymentMethodStats.map(s => s.payment_method.toUpperCase()),
                    datasets: [{
                        data: paymentMethodStats.map(s => s.total),
                        backgroundColor: ['rgb(59, 130, 246)', 'rgb(16, 185, 129)']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Chart Status Pembayaran (Doughnut)
            new Chart(document.getElementById('paymentStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: paymentStatusStats.map(s => s.payment_status.toUpperCase()),
                    datasets: [{
                        data: paymentStatusStats.map(s => s.total),
                        backgroundColor: ['rgb(16, 185, 129)', 'rgb(245, 158, 11)', 'rgb(239, 68, 68)']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Handle Year Change untuk Monthly Chart
            document.getElementById('yearSelect').addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('year', this.value);
                window.location.href = url.toString();
            });
        </script>
    @endpush
@endsection
