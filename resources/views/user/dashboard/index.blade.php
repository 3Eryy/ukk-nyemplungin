@extends('layouts.user.index')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 p-6 md:p-8 bg-white">
        <!-- Statistik Cards -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Peminjaman</p>
                    <p class="text-2xl font-bold">{{ $totalRentals }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Peminjaman Aktif</p>
                    <p class="text-2xl font-bold">{{ $activeRentals }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Selesai</p>
                    <p class="text-2xl font-bold">{{ $completedRentals }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 mr-4">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Keranjang</p>
                    <p class="text-2xl font-bold">{{ $cartCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Rentals -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Peminjaman Terbaru</h2>
                </div>
                <div class="p-6">
                    @if ($recentRentals->isEmpty())
                        <p class="text-gray-500 text-center">Belum ada peminjaman</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($recentRentals as $rental)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="font-semibold">#{{ $rental->id }}</span>
                                            <span class="text-sm text-gray-500 ml-2">
                                                {{ \Carbon\Carbon::parse($rental->created_at)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                        @if ($rental->status == 'menunggu') bg-yellow-100 text-yellow-800
                                        @elseif($rental->status == 'disetujui') bg-blue-100 text-blue-800
                                        @elseif($rental->status == 'selesai') bg-green-100 text-green-800
                                        @elseif($rental->status == 'ditolak') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ $rental->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ $rental->payment ? $rental->payment->count() : 0 }} item •
                                        Total: Rp {{ number_format($rental->total_price, 0) }}
                                    </p>
                                    <a href="{{ route('user.rentals.show', $rental->id) }}"
                                        class="text-blue-600 text-sm hover:underline">
                                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('user.rentals.index') }}" class="text-blue-600 hover:underline">
                                Lihat Semua Peminjaman
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Aksi Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('user.rentals.create') }}"
                        class="block w-full bg-blue-600 text-white text-center px-4 py-3 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Sewa Barang Baru
                    </a>
                    <a href="{{ route('user.cart.index') }}"
                        class="block w-full bg-green-600 text-white text-center px-4 py-3 rounded-lg hover:bg-green-700">
                        <i class="fas fa-shopping-cart mr-2"></i>Lihat Keranjang
                    </a>
                </div>
            </div>

            <!-- Recommended Equipment -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Rekomendasi untuk Anda</h2>
                <div class="space-y-4">
                    @foreach ($recommendedEquipments as $equipment)
                        <div class="flex items-center space-x-3">
                            @if ($equipment->image)
                                <img src="{{ $equipment->image }}" alt="{{ $equipment->name }}"
                                    class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-camera text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $equipment->name }}</h3>
                                <p class="text-sm text-gray-600">Rp {{ number_format($equipment->hourly_price, 0) }}/hari
                                </p>
                            </div>
                            <form action="{{ route('user.cart.add', $equipment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-cart-plus text-xl"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
