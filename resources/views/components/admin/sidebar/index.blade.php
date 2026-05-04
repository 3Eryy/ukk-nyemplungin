<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Nyemplung.in</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nyemplung: {
                            blue: '#000DFB',
                            black: '#1E1E1E',
                            white: '#FFFFFF',
                            red: '#F63049',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Container Utama Sidebar -->
    <div x-data="{ open: true }" class="flex h-screen w-fit shadow-xl relative sticky top-0">

        <!-- 1. BAGIAN KIRI (STRIP BIRU & TOGGLE) -->
        <div class="w-16 md:w-20 bg-nyemplung-white flex flex-col items-center justify-between py-5 text-white z-20 relative border-r border-gray-100">
            
            <!-- Logo -->
            <img src="{{ asset('images/logo.png')}}" alt="Logo" class="w-12 h-12 object-contain rounded-full p-1"/>

            <!-- Teks Vertikal -->
            <div class="flex-1 flex items-center justify-center">
                <h1 class="rotate-180 text-xl text-black font-bold tracking-wider whitespace-nowrap" style="writing-mode: vertical-rl;">
                    Nyemplung.in
                </h1>
            </div>

            <!-- Tombol Toggle -->
            <button @click="open = !open" class="hover:bg-gray-100 p-2 rounded-full transition focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-8 w-8 transition-transform duration-300 stroke-black"
                     :class="!open ? 'rotate-180' : ''" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- 2. BAGIAN KANAN (MENU NAVIGASI) -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="-translate-x-full opacity-0"
             class="w-64 bg-nyemplung-white flex flex-col justify-between border-r border-gray-200 origin-left">
            
            <!-- LIST MENU -->
            <div class="p-4 space-y-2 overflow-y-auto">
                
                <!-- MENU: DASHBOARD -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.dashboard') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Dashboard</span>
                </a>

                {{-- MENU: KEUANGAN --}}
                {{-- <a href="{{ route('admin.finance.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.finance*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Keuangan</span>
                </a> --}}

                <!-- MENU: MANAJEMEN USER -->
                <!-- Pakai tanda bintang (*) agar halaman create/edit user tetap aktif -->
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.users*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Manajemen User</span>
                </a>

                <!-- MENU: MANAJEMEN ALAT -->
                <a href="{{ route('admin.equipments') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.equipments*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Manajemen Alat</span>
                </a>

                <!-- MENU: KATEGORI -->
                <a href="{{ route('admin.equipment-categories') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.equipment-categories*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Kategori</span>
                </a>

                <!-- MENU: DATA PENYEWAAN (RENTAL) -->
                <a href="{{ route('admin.rentals.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.rentals*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Data Penyewaan</span>
                </a>

                <!-- MENU: PENGEMBALIAN -->
                <a href="{{ route('admin.return.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.return*') 
                      ? 'bg-nyemplung-blue text-white shadow-md hover:scale-[1.02]' 
                      : 'text-nyemplung-black hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="font-medium text-sm whitespace-nowrap">Pengembalian</span>
                </a>

            </div>

            <!-- FOOTER SIDEBAR: PROFILE & LOGOUT -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <!-- Avatar Inisial -->
                        <div class="w-10 h-10 shrink-0 rounded-full bg-orange-400 flex items-center justify-center text-nyemplung-black border border-orange-200">
                            <h1 class="font-bold">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </h1>
                        </div>
                        <!-- Info User -->
                        <div class="leading-tight whitespace-nowrap">
                            <p class="text-sm font-bold text-nyemplung-black">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role->name ?? 'Administrator' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Logout -->
                <form action="{{ route('logout.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-nyemplung-red text-white flex items-center justify-center gap-2 py-2.5 rounded-lg font-medium shadow hover:bg-blue-800 transition whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>