@extends('layouts.admin.index')

<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@section('content')
    <div class="p-6 md:p-8 min-h-screen bg-white">

        <!-- 1. HEADER -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manajemen User</h1>
            <p class="text-gray-500 mt-1">Kelola data user dengan Mudah</p>
        </div>

        <!-- 2. STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card: Admin -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-start justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-900 mb-2">Jumlah Admin</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $jumlahAdmin }}</h3>
                    <p class="text-xs text-gray-500 font-medium">Total semua admin</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-white">
                    <!-- Icon Shield -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>

            <!-- Card: Petugas -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-start justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-900 mb-2">Jumlah Petugas</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $jumlahPetugas }}</h3>
                    <p class="text-xs text-gray-500 font-medium">Total semua petugas</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-white">
                    <!-- Icon User -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <!-- Card: Peminjam -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-start justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-900 mb-2">Jumlah Peminjam</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $jumlahUser }}</h3>
                    <p class="text-xs text-gray-500 font-medium">Total semua peminjam</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-white">
                    <!-- Icon User Group -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#000DFB">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. TOOLBAR (Search, Filter, Button) -->
        <div x-data="{ open: false }" class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">

            <!-- KIRI (Search + Filter) -->
            <div class="flex gap-4 w-full md:w-auto">

                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <form method="GET" action="{{ route('admin.users') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..."
                            class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg w-full">
                    </form>
                </div>

                <!-- Filter -->
                <form method="GET" action="{{ route('admin.users') }}">
                    <select name="role" onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 rounded-lg bg-white">

                        <option value="">Semua</option>

                        @foreach ($roleUser as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- KANAN (Button Tambah User) -->
            <button @click="open = true" class="bg-[#000DFB] text-white px-4 py-2 rounded-lg">
                + Tambah User
            </button>

            <!-- MODAL -->
            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                style="display: none;">

                <div @click.away="open = false" class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

                    <h2 class="text-lg font-semibold mb-4">Tambah User</h2>

                    <form method="POST" action="{{ route('admin.users.insert') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm mb-1">Nama</label>
                            <input type="text" name="name" class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm mb-1">Email</label>
                            <input type="email" name="email" class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm mb-1">Telepon</label>
                            <input type="text" name="phone" class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm mb-1">Role</label>
                            <select name="role_id" class="w-full border rounded-lg px-3 py-2">
                                @foreach ($roleUser as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm mb-1">Password</label>
                            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded-lg">
                                Batal
                            </button>

                            <button type="submit" class="px-4 py-2 bg-[#000DFB] text-white rounded-lg">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- 4. TABLE -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F9F8F6] text-black">
                            <th class="py-4 px-6 font-semibold text-sm w-16">NO</th>
                            <th class="py-4 px-6 font-semibold text-sm">Nama</th>
                            <th class="py-4 px-6 font-semibold text-sm">Email</th>
                            <th class="py-4 px-6 font-semibold text-sm">Telepon</th>
                            <th class="py-4 px-6 font-semibold text-sm text-center">Role</th>
                            <th class="py-4 px-6 font-semibold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @foreach ($dataUser as $data)
                            <tr x-data="{ openEdit: false }" class="hover:bg-gray-50">
                                <td class="py-4 px-6 text-gray-800">{{ $loop->iteration }}</td>
                                <td class="py-4 px-6 font-medium text-gray-900">{{ $data->name }}</td>
                                <td class="py-4 px-6 text-gray-600">{{ $data->email }}</td>
                                <td class="py-4 px-6 text-gray-600">{{ $data->phone }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if ($data->role->name == 'admin')
                                        <span
                                            class="inline-block px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-semibold">
                                            {{ $data->role->name }}
                                        </span>
                                    @elseif ($data->role->name == 'petugas')
                                        <span
                                            class="inline-block px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-semibold">
                                            {{ $data->role->name }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-semibold">
                                            {{ $data->role->name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openEdit = true"
                                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <!-- MODAL EDIT -->
                                        <div x-show="openEdit"
                                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                            style="display: none;">

                                            <div @click.away="openEdit = false"
                                                class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

                                                <h2 class="text-lg font-semibold mb-4">Edit User</h2>

                                                <form action="{{ route('admin.users.update', $data->id)}}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label class="block text-left">Nama</label>
                                                        <input type="text" name="name" value="{{ $data->name }}"
                                                            class="w-full border rounded-lg px-3 py-2">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="block text-left">Email</label>
                                                        <input type="email" name="email" value="{{ $data->email }}"
                                                            class="w-full border rounded-lg px-3 py-2">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="block text-left">Telepon</label>
                                                        <input type="text" name="phone" value="{{ $data->phone }}"
                                                            class="w-full border rounded-lg px-3 py-2">
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="block text-left">Role</label>
                                                        <select name="role_id" class="w-full border rounded-lg px-3 py-2">
                                                            @foreach ($roleUser as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $data->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="openEdit = false"
                                                            class="px-4 py-2 bg-gray-300 rounded-lg">
                                                            Batal
                                                        </button>

                                                        <button type="submit"
                                                            class="px-4 py-2 bg-[#000DFB] text-white rounded-lg">
                                                            Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.users.delete', $data->id)}}" method="POST" class="pt-4">
                                            @csrf
                                            @method('DELETE')

                                            <button class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
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

        <!-- 5. PAGINATION -->
        <div class="flex justify-end items-center mt-6 gap-2">
            {{ $dataUser->links() }}
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
@endsection
