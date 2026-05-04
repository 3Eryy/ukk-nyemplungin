@extends('layouts.petugas.index')

@section('content')
    <!-- Container Utama Konten -->
    <div class="p-6 md:p-8 min-h-screen bg-white">

        <!-- 1. HEADER -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1E1E1E]">Dashboard Admin</h1>
            <p class="text-gray-500 mt-1">Selamat datang kembali {{ Auth::user()->name}} Berikut ringkasan aktivitas terbaru.</p>
        </div>

        <!-- 2. STATS CARDS (4 Kotak Atas) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Card 1: Total Alat -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Total Alat</p>
                        <h3 class="text-3xl font-bold text-[#1E1E1E] mt-2">{{ $totalEquipments }}</h3>
                    </div>
                    <div class="bg-white p-3 rounded-xl text-white">
                        <!-- Icon Box/Archive -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4 font-medium">Total semua alat</p>
            </div>

            <!-- Card 2: Total Peminjaman -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Total Peminjaman</p>
                        <h3 class="text-3xl font-bold text-[#1E1E1E] mt-2">{{ $totalRentals }}</h3>
                    </div>
                    <div class="bg-white p-3 rounded-xl text-white">
                        <!-- Icon Document -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4 font-medium">Total semua peminjaman</p>
            </div>

            <!-- Card 3: Alat Tersedia -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Alat Tersedia</p>
                        <h3 class="text-3xl font-bold text-[#1E1E1E] mt-2">{{ $totalReadeyItems }}</h3>
                    </div>
                    <div class="bg-white p-3 rounded-xl text-white">
                        <!-- Icon Box Open -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4 font-medium">Total semua yang tersedia</p>
            </div>

            <!-- Card 4: Alat Dipinjam -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Alat Dipinjam</p>
                        <h3 class="text-3xl font-bold text-[#1E1E1E] mt-2">{{ $totalBorrowItems }}</h3>
                    </div>
                    <div class="bg-transparent p-3 rounded-xl text-white">
                        <!-- Icon Refresh/Clock -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4 font-medium">Total semua yang dipinjam</p>
            </div>
        </div>

        <!-- 3. CHART SECTION -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-[#1E1E1E] mb-4">Distribusi penyewaan disetiap bulan</h2>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <!-- Canvas untuk Chart.js -->
                <div class="h-80 w-full">
                    <canvas id="rentalChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. AKTIVITAS TERBARU -->
        <div>
            <h2 class="text-lg font-bold text-[#1E1E1E] mb-4">Aktivitas terbaru</h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                <!-- Item 1 -->
                @foreach ($aktivitasTerbaru as $aktivitas)
                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between py-4 border-b border-gray-100 last:border-0 last:pb-0 first:pt-0 gap-4">
                        <div class="flex items-center gap-4">
                            <!-- Avatar Inisial -->
                            <div
                                class="w-12 h-12 rounded-full bg-yellow-400 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                                {{ strtoupper(substr($aktivitas->user_name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E1E1E]">{{ $aktivitas->user_name }}</h4>
                                <p class="text-sm text-gray-600">Menyewa {{ $aktivitas->equipment_name }}</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            @if ($aktivitas->status == 'disetujui')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-500 rounded-lg text-xs font-semibold mb-1">
                                    Disetujui
                                </span>
                            @elseif ($aktivitas->status == 'ditolak')
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-500 rounded-lg text-xs font-semibold mb-1">
                                    Ditolak
                                </span>
                            @elseif ($aktivitas->status == 'selesai')
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-500 rounded-lg text-xs font-semibold mb-1">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-500 rounded-lg text-xs font-semibold mb-1">
                                    Menunggu
                                </span>
                            @endif
                            <p class="text-xs text-gray-400">{{ $aktivitas->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('rentalChart').getContext('2d');

            // Data Dummy Sesuai Gambar
            const data = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: {{ $year}},
                    data: @json( $dataGrafik ),
                    backgroundColor: '#3b82f6', // Tailwind blue-500 (bisa diganti '#000DFB' jika ingin biru tua pekat)
                    borderRadius: 4, // Membuat sudut batang sedikit bulat
                    hoverBackgroundColor: '#000DFB'
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6', // Grid tipis
                                borderDash: [5, 5]
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            } // Hilangkan garis border sumbu Y
                        },
                        x: {
                            grid: {
                                display: false, // Hilangkan grid vertikal
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 10
                                },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
@endsection
