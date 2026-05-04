@extends('layouts.admin.index')

<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@section('content')
    <div class="container mx-auto px-4 py-6 font-sans text-gray-800">

        {{-- Judul dan Subjudul --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-black mb-1">Manajemen Kategori</h1>
            <p class="text-gray-500 text-sm">Kelola data kategori alat dengan Mudah</p>
        </div>

        {{-- Flash Message (Opsional, untuk notifikasi sukses/gagal) --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Toolbar: Pencarian dan Tombol Tambah --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            {{-- Form Pencarian --}}
            <form action="{{ route('admin.equipment-categories') }}" method="GET" class="w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    {{-- Icon Kaca Pembesar (SVG) --}}
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-600 placeholder-gray-400"
                    placeholder="Cari......">
            </form>

            {{-- Tombol Tambah --}}
            <button onclick="toggleModal('addModal')"
                class="bg-[#000DFB] hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-lg flex items-center shadow-lg transition duration-200">
                <span class="mr-2 text-xl font-bold">+</span> Tambah
            </button>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    {{-- Header Table --}}
                    <thead>
                        <tr class="bg-[#F9F8F6] text-black text-left">
                            <th class="py-4 px-6 font-semibold text-sm tracking-wider w-16">No</th>
                            <th class="py-4 px-6 font-semibold text-sm tracking-wider w-1/4">Nama Kategori</th>
                            <th class="py-4 px-6 font-semibold text-sm tracking-wider w-1/2">Deskripsi</th>
                            <th class="py-4 px-6 font-semibold text-sm tracking-wider text-center w-1/6">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body Table --}}
                    <tbody class="divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- Kolom No --}}
                                <td class="py-6 px-6 text-gray-900 font-medium">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Kolom Nama --}}
                                <td class="py-6 px-6 text-gray-900 text-lg font-medium">
                                    {{ $category->name }}
                                </td>

                                {{-- Kolom Deskripsi --}}
                                <td class="py-6 px-6 text-gray-600 text-sm whitespace-normal leading-relaxed">
                                    {{ Str::limit($category->description, 150, '...') }}
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="py-6 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        {{-- Tombol Edit --}}
                                        <button type="button" onclick="openEditModal(this)" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center hover:bg-blue-200 transition duration-200 group">
                                            {{-- Icon Pencil --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-[#000DFB] group-hover:text-blue-800" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.equipment-categories.delete', $category->id) }}"
                                            method="POST"
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
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-6 text-center text-gray-500">
                                    Data kategori belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop (Latar Gelap) -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="toggleModal('addModal')"></div>

            <!-- Trick untuk centering browser lama -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Konten Modal -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <!-- Header Modal -->
                <div class="bg-[#F9F8F6] px-4 py-3 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-black" id="modal-title">
                        Tambah Kategori Baru
                    </h3>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.equipment-categories.insert') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="space-y-4">

                            <!-- Input Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                                <input type="text" name="name" id="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Contoh: Wetsuit" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Input Deskripsi -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Masukkan deskripsi kategori...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Footer Modal (Tombol Aksi) -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#000DFB] text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" onclick="toggleModal('addModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeEditModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Konten Modal -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <!-- Header Modal -->
                <div class="bg-[#F9F8F6] px-4 py-3 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-black" id="modal-title">
                        Edit Kategori
                    </h3>
                </div>

                <!-- Form Edit -->
                <!-- Action form ini akan di-update oleh JavaScript -->
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT') <!-- Penting: Method spoofing untuk Update -->

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="space-y-4">

                            <!-- Input Nama -->
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama
                                    Kategori</label>
                                <input type="text" name="name" id="edit_name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required>
                            </div>

                            <!-- Input Deskripsi -->
                            <div>
                                <label for="edit_description"
                                    class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="edit_description" rows="3"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </div>

                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#000DFB] text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script JavaScript Sederhana -->
    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        // Opsional: Jika ada error validasi (server-side), modal tetap terbuka
        @if ($errors->any())
            document.getElementById('addModal').classList.remove('hidden');
        @endif

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openEditModal(button) {
            // 1. Ambil data dari tombol yang diklik
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');

            // 2. Isi value input form dengan data tersebut
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;

            // 3. Update URL Action Form
            // Kita buat template URL, lalu replace ':id' dengan ID yang sebenarnya
            let url = "{{ route('admin.equipment-categories.update', ':id') }}";
            url = url.replace(':id', id);

            document.getElementById('editForm').action = url;

            // 4. Tampilkan Modal
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>

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
@endsection
