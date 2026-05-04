@extends('layouts.admin.index')

<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@section('content')
    <div class="p-6 bg-white min-h-screen font-sans">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-black">Manajemen Alat</h1>
            <p class="text-gray-500 mt-1">Kelola data alat dengan Mudah</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Card 2 --}}
            <div class="border rounded-xl p-6 shadow-sm bg-white relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-black mb-1">Jumlah Tersedia</h3>
                        <p class="text-3xl font-bold">{{ $barangTersedia }}</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg text-white">
                        {{-- Icon Check --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Total semua alat yang tersedia</p>
            </div>

            <div class="border rounded-xl p-6 shadow-sm bg-white relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-black mb-1">Jumlah Rusak</h3>
                        <p class="text-3xl font-bold">{{ $barangRusak }}</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg text-white">
                        {{-- Icon Check --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Total semua alat yang rusak</p>
            </div>

            {{-- Card 3 --}}
            <div class="border rounded-xl p-6 shadow-sm bg-white relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-black mb-1">Total Alat</h3>
                        <p class="text-3xl font-bold">{{ $totalBarang }}</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg text-white">
                        {{-- Icon Tools/Settings --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="#000DFB">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Total semua alat</p>
            </div>
        </div>

        {{-- Toolbar (Search & Add) --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex gap-4 w-full md:w-auto">
                {{-- Search Input --}}
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text"
                        class="w-full py-2 pl-10 pr-4 text-gray-700 bg-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari.......">
                </div>

                {{-- Dropdown Filter --}}
                <div class="relative">
                    <select
                        class="block w-full px-4 py-2 pr-8 leading-tight bg-white border rounded-lg appearance-none focus:outline-none focus:border-gray-500 text-gray-700">
                        <option>Semua</option>
                        <option>Tersedia</option>
                        <option>Dipinjam</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Add Button --}}
            <button onclick="toggleModal('addEquipmentModal')"
                class="bg-[#000DFB] hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-[#F9F8F6] text-black">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">No</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Foto Barang</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Nama Barang</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Harga</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Stock</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Status Barang</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Status Stock</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($equipment as $equip)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 text-gray-900">{{ $loop->iteration }}</td>
                                <td class="py-4 px-4">
                                    <img src="{{ $equip->image }}" alt="{{ $equip->name }}"
                                        class="h-12 w-auto object-contain">
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-900 text-lg">{{ $equip->name }}</td>
                                <td class="py-4 px-4 text-gray-900">
                                    Rp. {{ number_format($equip->hourly_price, 0, ',', '.') }}/jam
                                </td>
                                <td class="py-4 px-4 text-gray-900">{{ $equip->stock }}</td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-500">
                                        {{ $equip->condition_status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-400">
                                        {{ $equip->available_status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex gap-2">
                                        <form>
                                            <button type="button"
                                                class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center hover:bg-blue-200 transition duration-200 group"
                                                onclick="openModal('editModal-{{ $equip->id }}')">
                                                <!-- Ganti jadi openModal -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="blue">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                        </form>
                                        {{-- button delete --}}
                                        <form action="{{ route('admin.equipments.delete', $equip->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center hover:bg-red-200 transition duration-200 group">
                                                {{-- Icon Trash --}}
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-red-500 group-hover:text-red-700" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

    {{-- Modal Tambah Equipment --}}
    <div id="addEquipmentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="toggleModal('addEquipmentModal')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form action="{{ route('admin.equipments.insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">
                                    Tambah Alat Baru
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nama Barang -->
                                    <div class="col-span-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                                        <input type="text" name="name" required
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Kategori (Pastikan Anda mengirim $categories dari controller jika ingin dinamis) -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori ID</label>
                                        <select name="category_id" required
                                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">Pilih Kategori</option>
                                            {{-- Contoh looping jika ada data kategori --}}
                                            @foreach ($category as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Harga Per Jam -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga / Jam</label>
                                        <input type="number" name="hourly_price" required min="0"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>

                                    <!-- Stock -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                                        <input type="number" name="stock" required min="0"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>

                                    <!-- Kondisi Fisik -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi Fisik</label>
                                        <select name="condition_status" required
                                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                    </div>

                                    <!-- Status Ketersediaan -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Status
                                            Ketersediaan</label>
                                        <select name="available_status" required
                                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="tersedia">Tersedia</option>
                                            <option value="dipinjam">Dipinjam</option>
                                            <option value="tidak_tersedia">Tidak Tersedia</option>
                                        </select>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-span-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                                        <textarea name="description" rows="3"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                    </div>

                                    <!-- Foto Barang -->
                                    <div class="col-span-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Barang</label>
                                        <input type="text" name="image" accept="image/*"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <p class="text-xs text-gray-500 mt-1">URL Image</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#000DFB] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Data
                        </button>
                        <button type="button" onclick="toggleModal('addEquipmentModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk Toggle Modal --}}
    <script>
        function openModal(modalID) {
            console.log('Opening modal:', modalID); // Untuk debug
            document.getElementById(modalID).classList.remove('hidden');
        }

        function closeModal(modalID) {
            console.log('Closing modal:', modalID); // Untuk debug
            document.getElementById(modalID).classList.add('hidden');
        }

        // Optional: Tetap pertahankan toggleModal jika digunakan di tempat lain
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                openModal(modalID);
            } else {
                closeModal(modalID);
            }
        }
    </script>

    {{-- Loop Modal Edit --}}
    @foreach ($equipment as $item)
        <div id="editModal-{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    onclick="closeModal('editModal-{{ $item->id }}')"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Panel Modal -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                    <!-- Form Update -->
                    <form action="{{ route('admin.equipments.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Penting untuk method update --}}

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Barang: {{ $item->name }}</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Nama Barang -->
                                <div class="col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                                    <input type="text" name="name" value="{{ $item->name }}" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Kategori (Sesuaikan value option dengan data) -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori ID</label>
                                    <select name="category_id" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                        {{-- Gunakan loop kategori jika ada variable $categories --}}
                                    </select>
                                </div>

                                <!-- Harga -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Harga / Jam</label>
                                    <input type="number" name="hourly_price"
                                        value="{{ $item->price ?? $item->hourly_price }}" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>

                                <!-- Stock -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                                    <input type="number" name="stock" value="{{ $item->stock }}" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>

                                <!-- Kondisi -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi</label>
                                    <select name="condition_status" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                        <option value="Baik" {{ $item->condition_status == 'Baik' ? 'selected' : '' }}>
                                            Baik</option>
                                        <option value="rusak"
                                            {{ $item->condition_status == 'Rusak Ringan' ? 'selected' : '' }}>rusak
                                        </option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                                    <select name="available_status" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                        <option value="tersedia"
                                            {{ $item->available_status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="dipinjam"
                                            {{ $item->available_status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="tidak_tersedia"
                                            {{ $item->available_status == 'Habis' ? 'selected' : '' }}>
                                            Tidak Tersedia</option>
                                    </select>
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                                    <textarea name="description" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $item->description }}</textarea>
                                </div>

                                <!-- Foto (Optional) -->
                                <div class="col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto (Kosongkan jika
                                        tidak berubah)</label>
                                    <div class="flex items-center gap-4">
                                        @if ($item->image)
                                            <img src="{{ $item->image }}" class="h-10 w-10 object-cover rounded">
                                        @endif
                                        <input type="text" name="image"
                                            class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeModal('editModal-{{ $item->id }}')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
