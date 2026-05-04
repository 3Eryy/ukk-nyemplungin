@extends('layouts.admin.index')

<head>
    <!-- Font Awesome 6 (Free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen font-sans">

        <!-- Header Page -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Data Pengembalian Alat</h1>
            <p class="text-gray-500 mt-1 text-sm">Berikut adalah data pengembalian.</p>
        </div>

        <!-- Kiri: Search & Filter -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
                <!-- Input Search -->
                <div class="flex-1 min-w-[250px]">
                    <input type="text" name="search"
                        class="w-full px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                        placeholder="Cari nama penyewa..." value="{{ request('search') }}">
                </div>

                <!-- Input Tanggal -->
                <div class="w-full sm:w-auto">
                    <input type="date" name="tanggal"
                        class="w-full px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                        value="{{ request('tanggal') }}">
                </div>

                <!-- Group Tombol Filter & Reset -->
                <div class="flex flex-row items-stretch gap-2 w-full sm:w-auto">
                    <!-- Tombol Filter -->
                    <button type="submit"
                        class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                        <span class="inline sm:hidden">Cari</span>
                    </button>

                    <!-- Tombol Reset -->
                    <a href="{{ route('admin.rentals.index') }}"
                        class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Reset</span>
                        <span class="inline sm:hidden">Hapus</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Kanan: Tombol Aksi -->
        <div class="flex flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto mb-6">
            <!-- Tombol Input Pengembalian -->
            <button type="button"
                class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-medium text-white bg-[#000DFB] rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2"
                data-modal-toggle="createReturnModal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="hidden sm:inline">Input Pengembalian</span>
                <span class="inline sm:hidden">Pengembalian</span>
            </button>

            <!-- Tombol Export -->
            <a href="{{ route('admin.return.export-pdf') }}" target="_blank"
                class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-medium text-black bg-white-600 rounded-lg hover:bg-[#000DFB] hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="hidden sm:inline">Export PDF</span>
                <span class="inline sm:hidden">Export</span>
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <!-- Header Table (Warna Putih Tulang #F9F8F6) -->
                    <thead
                        class="bg-[#F9F8F6] text-gray-700 font-bold uppercase text-xs tracking-wider border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 rounded-tl-xl">NO</th>
                            <th scope="col" class="px-6 py-4">Nama Peminjam</th>
                            <th scope="col" class="px-6 py-4">Nama Barang</th>
                            <th scope="col" class="px-6 py-4">Tanggal dipinjam</th>
                            <th scope="col" class="px-6 py-4">Tanggal kembali</th>
                            <th scope="col" class="px-6 py-4">Total harga</th>
                            <th scope="col" class="px-6 py-4 text-center rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($returns as $index => $return)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <!-- NO -->
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $returns->firstItem() + $index }}.
                                </td>

                                <!-- Nama Peminjam -->
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $return->rental->user->name ?? '-' }}
                                </td>

                                <!-- Nama Barang -->
                                <td class="px-6 py-4 text-gray-600">
                                    @php
                                        $equipments = $return->rental->rentalItems
                                            ->map(function ($ri) {
                                                return $ri->equipment->name ?? '-';
                                            })
                                            ->join(', ');
                                    @endphp
                                    <span title="{{ $equipments }}" class="truncate block max-w-[150px]">
                                        {{ Str::limit($equipments, 20) }}
                                    </span>
                                </td>

                                <!-- Tanggal Dipinjam -->
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    <i class="far fa-calendar text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($return->rental->rental_start)->format('Y-m-d') }}
                                </td>

                                <!-- Tanggal Kembali -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="far fa-calendar text-gray-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}</span>
                                    </div>
                                </td>

                                <!-- Total Harga -->
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    Rp. {{ number_format($return->rental->total_price, 0, ',', '.') }}
                                </td>

                                <!-- Aksi -->
                                <td class="py-5 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Tombol View Detail --}}
                                        <button type="button"
                                            onclick="document.getElementById('detailModal-{{ $return->id }}').classList.remove('hidden')"
                                            class="w-9 h-9 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 flex items-center justify-center transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Edit Status (Pencil) --}}
                                        <button type="button"
                                            onclick="document.getElementById('editModal-{{ $return->id }}').classList.remove('hidden')"
                                            class="w-9 h-9 rounded-lg bg-blue-100 text-[#000DFB] hover:bg-blue-200 flex items-center justify-center transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- MODAL EDIT -->
                                        <div id="editModal-{{ $return->id }}"
                                            class="fixed inset-0 z-50 hidden overflow-y-auto"
                                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <!-- Overlay Background -->
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                            <div
                                                class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                                <div
                                                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                                    <!-- Form Start -->
                                                    <form action="{{ route('admin.return.update', $return->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Modal Header & Close Button -->
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="text-lg font-bold leading-6 text-gray-900"
                                                                    id="modal-title">Detail pengembalian</h3>
                                                                <button type="button"
                                                                    onclick="document.getElementById('editModal-{{ $return->id }}').classList.add('hidden')"
                                                                    class="text-gray-400 hover:text-gray-500">
                                                                    <span class="sr-only">Close</span>
                                                                    <svg class="h-6 w-6" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <!-- Gray Bar Title -->
                                                            <div
                                                                class="bg-gray-100 rounded-md py-2 px-4 mb-4 text-center text-sm font-medium text-gray-700">
                                                                Pengembalian
                                                            </div>

                                                            <!-- Content List (Grid Layout) -->
                                                            <div class="space-y-4 text-sm">

                                                                <!-- Daftar Barang (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Daftar barang
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        @foreach ($return->rental->rentalItems as $rentalItem)
                                                                            {{ $rentalItem->equipment->name ?? 'Unknown Item' }}
                                                                            @if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <!-- Nama Peminjam (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Nama peminjam
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->rental->user->name ?? '-' }}</div>
                                                                </div>

                                                                <!-- Tanggal Peminjaman (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Tanggal peminjaman
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($return->rental->rental_start)->format('Y-m-d') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Tanggal Kembali / Jatuh Tempo (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Tanggal kembali
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($return->rental->rental_end)->format('Y-m-d') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Tanggal Dikembalikan (EDITABLE) -->
                                                                <div class="grid grid-cols-3 gap-4 items-center">
                                                                    <div class="font-bold text-gray-900">Tanggal
                                                                        dikembalikan</div>
                                                                    <div class="col-span-2">
                                                                        <input type="date" name="return_date"
                                                                            value="{{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}"
                                                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                                    </div>
                                                                </div>

                                                                <!-- Total Harga (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Total harga</div>
                                                                    <div class="col-span-2 text-gray-600">Rp.
                                                                        {{ number_format($return->rental->total_price ?? 0, 0, ',', '.') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Total Barang (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Total barang</div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->rental->rentalItems->count() }}</div>
                                                                </div>

                                                                <!-- Catatan (EDITABLE) -->
                                                                <div class="grid grid-cols-3 gap-4 items-start">
                                                                    <div class="font-bold text-gray-900">Catatan</div>
                                                                    <div class="col-span-2">
                                                                        <textarea name="note" rows="2"
                                                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ $return->note ?? '' }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <!-- Kondisi (EDITABLE) -->
                                                                <div class="grid grid-cols-3 gap-4 items-center">
                                                                    <div class="font-bold text-gray-900">Kondisi</div>
                                                                    <div class="col-span-2">
                                                                        <select name="condition"
                                                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                                            <option value="Baik"
                                                                                {{ ($return->condition ?? '') == 'Baik' ? 'selected' : '' }}>
                                                                                Baik</option>
                                                                            <option value="Rusak Ringan"
                                                                                {{ ($return->condition ?? '') == 'Rusak Ringan' ? 'selected' : '' }}>
                                                                                Rusak Ringan</option>
                                                                            <option value="Rusak Berat"
                                                                                {{ ($return->condition ?? '') == 'Rusak Berat' ? 'selected' : '' }}>
                                                                                Rusak Berat</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Footer Buttons -->
                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                                            <button type="submit"
                                                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto">
                                                                Simpan Perubahan
                                                            </button>
                                                            <button type="button"
                                                                onclick="document.getElementById('editModal-{{ $return->id }}').classList.add('hidden')"
                                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                                Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <!-- Form End -->

                                                </div>
                                            </div>
                                        </div>

                                        <div id="detailModal-{{ $return->id }}"
                                            class="fixed inset-0 z-50 hidden overflow-y-auto"
                                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <!-- Overlay Background -->
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                            <div
                                                class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                                <div
                                                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                                    <!-- Form Start -->
                                                    <form>
                                                        <!-- Modal Header & Close Button -->
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="text-lg font-bold leading-6 text-gray-900"
                                                                    id="modal-title">Detail pengembalian</h3>
                                                                <button type="button"
                                                                    onclick="document.getElementById('detailModal-{{ $return->id }}').classList.add('hidden')"
                                                                    class="text-gray-400 hover:text-gray-500">
                                                                    <span class="sr-only">Close</span>
                                                                    <svg class="h-6 w-6" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <!-- Gray Bar Title -->
                                                            <div
                                                                class="bg-gray-100 rounded-md py-2 px-4 mb-4 text-center text-sm font-medium text-gray-700">
                                                                Pengembalian
                                                            </div>

                                                            <!-- Content List (Grid Layout) -->
                                                            <div class="space-y-4 text-sm">

                                                                <!-- Daftar Barang (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Daftar barang
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        @foreach ($return->rental->rentalItems as $rentalItem)
                                                                            {{ $rentalItem->equipment->name ?? 'Unknown Item' }}
                                                                            @if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <!-- Nama Peminjam (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Nama peminjam
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->rental->user->name ?? '-' }}</div>
                                                                </div>

                                                                <!-- Tanggal Peminjaman (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Tanggal peminjaman
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($return->rental->rental_start)->format('Y-m-d') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Tanggal Kembali / Jatuh Tempo (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Tanggal kembali
                                                                    </div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($return->rental->rental_end)->format('Y-m-d') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Tanggal Dikembalikan (EDITABLE) -->
                                                                <div class="grid grid-cols-3 gap-4 items-center">
                                                                    <div class="font-bold text-gray-900">Tanggal
                                                                        dikembalikan</div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}

                                                                    </div>
                                                                </div>

                                                                <!-- Total Harga (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Total harga</div>
                                                                    <div class="col-span-2 text-gray-600">Rp.
                                                                        {{ number_format($return->rental->total_price ?? 0, 0, ',', '.') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Total Barang (Read Only) -->
                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Total barang</div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->rental->rentalItems->count() }}</div>
                                                                </div>

                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Status</div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->condition }}
                                                                    </div>
                                                                </div>

                                                                <div class="grid grid-cols-3 gap-4">
                                                                    <div class="font-bold text-gray-900">Catatan</div>
                                                                    <div class="col-span-2 text-gray-600">
                                                                        {{ $return->notes ?? '-' }}
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Footer Buttons -->
                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                                            <button type="button"
                                                                onclick="document.getElementById('detailModal-{{ $return->id }}').classList.add('hidden')"
                                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                                Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <!-- Form End -->

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('admin.return.destroy', $return->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 bg-white">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <p class="text-base">Tidak ada data pengembalian ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Create Return --}}
        <div id="createReturnModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">Form Pengembalian Barang</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-500"
                                data-modal-toggle="createReturnModal">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="returnForm" class="mt-4">
                            @csrf

                            {{-- Pilih Rental --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Transaksi Rental <span class="text-red-500">*</span>
                                </label>
                                <select name="rental_id" id="rental_id" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Transaksi --</option>
                                    @foreach ($availableRentals as $rental)
                                        <option value="{{ $rental->id }}" data-rental-end="{{ $rental->rental_end }}"
                                            data-user="{{ $rental->user->name }}">
                                            {{ $rental->user->name }} -
                                            {{ \Carbon\Carbon::parse($rental->rental_start)->format('d/m/Y') }}
                                            s/d {{ \Carbon\Carbon::parse($rental->rental_end)->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Info Rental --}}
                            <div id="rentalInfo" class="hidden mb-4 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-2">Informasi Rental:</h4>
                                <p>User: <span id="userName"></span></p>
                                <p>Batas Kembali: <span id="batasKembali"></span></p>
                                <div id="itemsList" class="mt-2">
                                    <p class="font-medium">Items:</p>
                                    <ul id="itemsListContainer" class="list-disc list-inside text-sm"></ul>
                                </div>
                            </div>

                            {{-- Tanggal Kembali --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Kembali <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="return_date" id="return_date" value="{{ date('Y-m-d') }}"
                                    required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            {{-- Perhitungan Denda --}}
                            <div id="fineCalculation" class="hidden mb-4 p-4 bg-yellow-50 rounded-lg">
                                <h4 class="font-semibold text-yellow-800 mb-2">Informasi Denda:</h4>
                                <p>Hari Terlambat: <span id="lateDays">0</span> hari</p>
                                <p class="text-lg font-bold">Denda: Rp <span id="fineAmount">0</span></p>
                                <p class="text-sm text-gray-600">(Rp 1.000 × hari terlambat)</p>
                            </div>

                            {{-- Kondisi Barang --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kondisi Barang <span class="text-red-500">*</span>
                                </label>
                                <select name="condition" id="condition" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Catatan tambahan (jika ada)"></textarea>
                            </div>
                        </form>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" id="submitReturn"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            data-modal-toggle="createReturnModal">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            {{ $returns->links() }}
        </div>
    </div>
    <script src="{{ asset('js/return/index.js') }}"></script>
@endsection
