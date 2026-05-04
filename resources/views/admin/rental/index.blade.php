@extends('layouts.admin.index')

@section('content')
    <div class="container mx-auto px-4 py-6 font-sans text-gray-800">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-black mb-1">Data Penyewaan Alat</h1>
            <p class="text-gray-500 text-sm">Berikut adalah data penyewaan.</p>
        </div>

        {{-- Summary Cards (Statistik) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Card Selesai --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Selesai</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $stats['selesai'] }}</h2>
                    <p class="text-xs text-gray-400 mt-2">Peminjaman yang selesai</p>
                </div>
                <div class="bg-white p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            {{-- Card Ditolak --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Ditolak</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $stats['ditolak'] }}</h2>
                    <p class="text-xs text-gray-400 mt-2">Peminjaman ditolak</p>
                </div>
                <div class="bg-white p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            {{-- Card Menunggu --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Menunggu</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $stats['menunggu'] }}</h2>
                    <p class="text-xs text-gray-400 mt-2">Menunggu persetujuan</p>
                </div>
                <div class="bg-white p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Card Dipinjam --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Dipinjam</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $stats['dipinjam'] }}</h2>
                    <p class="text-xs text-gray-400 mt-2">Berstatus dipinjam</p>
                </div>
                <div class="bg-white p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Toolbar & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <!-- Filter sederhana -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="flex flex-wrap items-center gap-2">
                        <!-- Input Search -->
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search"
                                class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="Cari nama..." value="{{ request('search') }}">
                        </div>

                        <!-- Input Tanggal -->
                        <div class="w-auto">
                            <input type="date" name="tanggal"
                                class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                value="{{ request('tanggal') }}">
                        </div>

                        <!-- Tombol Filter -->
                        <button type="submit"
                            class="px-6 py-2 text-white bg-[#000DFB] rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Filter
                            </span>
                        </button>

                        <!-- Tombol Reset -->
                        <a href="{{ route('admin.rentals.index') }}"
                            class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </span>
                        </a>
                    </form>
                </div>
            </div>

            <button
                class="bg-[#000DFB] hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-lg flex items-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <a href="{{ route('admin.rentals.export.pdf') }}">
                    Export PDF
                </a>
            </button>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#F9F8F6] text-black text-left">
                            <th class="py-4 px-6 font-semibold text-sm">NO</th>
                            <th class="py-4 px-6 font-semibold text-sm">Nama Peminjam</th>
                            <th class="py-4 px-6 font-semibold text-sm">Nama Barang</th>
                            <th class="py-4 px-6 font-semibold text-sm">Tanggal Dipinjam</th>
                            <th class="py-4 px-6 font-semibold text-sm">Tanggal Pengembalian</th>
                            <th class="py-4 px-6 font-semibold text-sm">Dikembalikan</th>
                            <th class="py-4 px-6 font-semibold text-sm">Total Harga</th>
                            <th class="py-4 px-6 font-semibold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rentals as $rental)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 px-6 text-gray-900">
                                    {{ $loop->iteration + ($rentals->currentPage() - 1) * $rentals->perPage() }}.</td>
                                <td class="py-5 px-6 text-gray-900 font-medium">{{ $rental->user->name ?? 'Unknown' }}</td>
                                <td class="py-5 px-6 text-gray-600">
                                    {{-- Menampilkan barang pertama + jumlah sisa jika ada banyak --}}
                                    @if ($rental->rentalItems->count() > 0)
                                        {{ $rental->rentalItems->first()->equipment->name ?? 'Item Terhapus' }}
                                        @if ($rental->rentalItems->count() > 1)
                                            <span
                                                class="text-xs text-blue-600 font-semibold">(+{{ $rental->rentalItems->count() - 1 }}
                                                lainnya)</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-5 px-6 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($rental->rental_start)->format('Y-m-d') }}
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($rental->rental_end)->format('Y-m-d') }}
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>

                                        {{ $rental->return_date ? \Carbon\Carbon::parse($rental->return_date)->format('Y-m-d') : 'Belum dikembalikan' }}
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-gray-900 font-medium">Rp.
                                    {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                                <td class="py-5 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Tombol View Detail --}}
                                        <button type="button"
                                            onclick='openDetailModal(@json($rental), @json($rental->rentalItems->load('equipment')))'
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
                                            onclick="openEditStatusModal('{{ $rental->id }}', '{{ $rental->status }}')"
                                            class="w-9 h-9 rounded-lg bg-blue-100 text-[#000DFB] hover:bg-blue-200 flex items-center justify-center transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('admin.rentals.destroy', $rental->id) }}" method="POST"
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
                                <td colspan="7" class="py-8 text-center text-gray-500">Tidak ada data penyewaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $rentals->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL 1: DETAIL PEMINJAMAN --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('detailModal')">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                {{-- Header Modal --}}
                <div class="bg-white px-4 py-4 sm:px-6 flex justify-between items-center border-b">
                    <h3 class="text-xl font-bold text-gray-900">Detail peminjaman</h3>
                    <button type="button" onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="px-6 py-6 bg-gray-50">
                    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Peminjaman</p>
                        <div class="grid grid-cols-3 gap-y-4 text-sm">
                            <div class="font-semibold text-gray-900">Daftar barang</div>
                            <div class="col-span-2 text-gray-700" id="detail_items_list">
                                <!-- Items inserted by JS -->
                            </div>

                            <div class="font-semibold text-gray-900">Nama peminjam</div>
                            <div class="col-span-2 text-gray-700" id="detail_user_name"></div>

                            <div class="font-semibold text-gray-900">Tanggal peminjaman</div>
                            <div class="col-span-2 text-gray-700" id="detail_start_date"></div>

                            <div class="font-semibold text-gray-900">Tanggal pengembalian</div>
                            <div class="col-span-2 text-gray-700" id="detail_end_date"></div>

                            <div class="font-semibold text-gray-900">Total harga</div>
                            <div class="col-span-2 text-gray-700" id="detail_total_price"></div>

                            <div class="font-semibold text-gray-900">Status peminjaman</div>
                            <div class="col-span-2">
                                <span id="detail_status_badge"
                                    class="px-3 py-1 rounded-full text-xs font-semibold"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Pembayaran (Opsional Visual) --}}
                    {{-- Bagian Pembayaran --}}
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Pembayaran</p>
                        <div class="grid grid-cols-3 gap-y-4 text-sm">
                            <div class="font-semibold text-gray-900">Status pembayaran</div>
                            <div class="col-span-2" id="detail_payment_status">-</div>

                            <div class="font-semibold text-gray-900">Metode pembayaran</div>
                            <div class="col-span-2" id="detail_payment_method">-</div>

                            <div class="font-semibold text-gray-900">Tanggal pembayaran</div>
                            <div class="col-span-2" id="detail_payment_date">-</div>

                            <div class="font-semibold text-gray-900">Jumlah dibayar</div>
                            <div class="col-span-2" id="detail_payment_amount">-</div>

                            <div class="font-semibold text-gray-900">Bukti Transfer</div>
                            <div class="col-span-2" id="detail_payment_proof">
                                {{-- Akan diisi oleh JavaScript --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeModal('detailModal')">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 2: EDIT STATUS (Pencil) --}}
    <div id="editStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                onclick="closeModal('editStatusModal')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                {{-- Header --}}
                <div class="bg-[#F9F8F6] px-4 py-3 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-black">Edit Status</h3>
                </div>

                <form id="editStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status
                                pinjaman</label>
                            <select name="status" id="status_select"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                                <option value="menunggu">Menunggu</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#000DFB] text-base font-medium text-white hover:bg-blue-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" onclick="closeModal('editStatusModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sweet Alert --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#000DFB'
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#000DFB'
            });
        </script>
    @endif

    <script src="{{ asset('js/rental/index.js') }}"></script>

    {{-- script untuk fungsi edit status --}}
    <script>
        function openEditStatusModal(id, currentStatus) {
            const modal = document.getElementById('editStatusModal');

            // Update Action URL Form - langsung gunakan route() Blade
            let url = '{{ route('admin.rentals.updateStatus', ':id') }}';
            url = url.replace(':id', id);
            document.getElementById('editStatusForm').action = url;

            // Set Selected Option pada Dropdown
            document.getElementById('status_select').value = currentStatus;

            modal.classList.remove('hidden');
        }
    </script>
@endsection
