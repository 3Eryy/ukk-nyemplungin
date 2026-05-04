<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard') - Nyemplungin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#000DFB',
                            dark: '#0009CC',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Navbar top accent bar */
        .navbar-accent {
            background: linear-gradient(90deg, #000DFB 0%, #000DFB 60%, #000000 100%);
            height: 3px;
        }

        /* Nav link animated underline */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 2px;
            background-color: #000DFB;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }

        [x-cloak] { display: none !important; }

        /* Cart badge pop animation */
        @keyframes badge-pop {
            0%   { transform: scale(1); }
            50%  { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .cart-badge {
            animation: badge-pop 0.4s ease;
        }

        /* Alert slide-in */
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-anim {
            animation: slide-down 0.35s ease forwards;
        }

        /* Dropdown item hover indent */
        .dropdown-item {
            transition: background 0.15s, padding-left 0.15s;
        }
        .dropdown-item:hover {
            padding-left: 1.25rem;
            background: #f0f3ff;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Top accent bar -->
    <div class="navbar-accent w-full"></div>

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">

            <!-- Logo + Brand -->
            <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 rounded-lg bg-[#000DFB] flex items-center justify-center shadow-md group-hover:shadow-[#000DFB]/40 transition-shadow duration-300">
                    <img src="{{ asset('images/logo.png')}}" alt="Logo" class="w-12 h-12 object-contain rounded-full p-1"/>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-black leading-none">
                    Nyemplung<span class="text-[#000DFB]">.in</span>
                </span>
            </a>

            <!-- Nav Links (desktop) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('user.dashboard') }}" class="nav-link text-sm font-semibold text-gray-700 hover:text-[#000DFB] transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('user.rentals.index') }}" class="nav-link text-sm font-semibold text-gray-700 hover:text-[#000DFB] transition-colors">
                    Peminjaman
                </a>
                <a href="{{ route('user.activity-logs.index') }}" class="nav-link text-sm font-semibold text-gray-700 hover:text-[#000DFB] transition-colors">
                    Aktivitas
                </a>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-3">

                <!-- Cart Icon -->
                <a href="{{ route('user.cart.index') }}" class="relative w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 hover:border-[#000DFB] hover:bg-[#000DFB]/5 transition-all duration-200 group">
                    <i class="fas fa-shopping-cart text-base text-gray-600 group-hover:text-[#000DFB] transition-colors"></i>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge absolute -top-1.5 -right-1.5 bg-[#000DFB] text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center space-x-2 px-3 py-2 rounded-xl border border-gray-200 hover:border-[#000DFB] hover:bg-[#000DFB]/5 transition-all duration-200"
                    >
                        <div class="w-7 h-7 rounded-lg bg-[#000DFB] flex items-center justify-center">
                            <span class="text-white text-xs font-bold leading-none">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-gray-800 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click.away="open = false"
                        class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50"
                    >
                        <!-- User Info Header -->
                        <div class="px-4 py-3 bg-[#000DFB]/5 border-b border-gray-100">
                            <p class="text-xs text-gray-500 font-medium">Masuk sebagai</p>
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('user.dashboard') }}" class="dropdown-item flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:text-[#000DFB]">
                                <i class="fas fa-tachometer-alt w-4 text-[#000DFB]/70"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('user.rentals.index') }}" class="dropdown-item flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:text-[#000DFB]">
                                <i class="fas fa-list w-4 text-[#000DFB]/70"></i>
                                <span>Peminjaman Saya</span>
                            </a>
                            <a href="{{ route('user.activity-logs.index') }}" class="dropdown-item flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:text-[#000DFB]">
                                <i class="fas fa-history w-4 text-[#000DFB]/70"></i>
                                <span>Aktivitas</span>
                            </a>
                        </div>

                        <div class="border-t border-gray-100 py-1">
                            <form method="POST" action="{{ route('logout.process') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full flex items-center space-x-3 px-4 py-2.5 text-sm text-red-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">

        @if(session('success'))
            <div class="alert-anim mb-5 flex items-start space-x-3 bg-white border-l-4 border-[#000DFB] text-gray-800 px-5 py-4 rounded-xl shadow-sm">
                <i class="fas fa-check-circle text-[#000DFB] mt-0.5 text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-anim mb-5 flex items-start space-x-3 bg-white border-l-4 border-red-500 text-gray-800 px-5 py-4 rounded-xl shadow-sm">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>