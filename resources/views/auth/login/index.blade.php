<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Nyemplungin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }

        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Section -->
        <div class="hidden lg:flex lg:w-1/2 bg-white items-center justify-center p-12">
            <div class="max-w-md text-center">
                <div class="mb-8 flex justify-center">
                    <div class="relative">
                        <svg width="280" height="280" viewBox="0 0 280 280" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <image href="{{ asset('images/logo.png') }}" alt="logo.png" width="280" height="280" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Selamat Datang Kembali di Nyemplung.In</h2>
                <p class="text-gray-600 text-lg">Platform penyewaan alat snorkeling dan selam terpercaya dengan layanan
                    profesional</p>
            </div>
        </div>

        <!-- Right Section - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Login</h1>
                    <p class="text-gray-600">Lanjutkan dengan nama dan password untuk masuk ke akun anda</p>
                </div>

                <form id="loginForm" class="space-y-5" method="POST" action="{{ route('login.process') }}">
                    @csrf
                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">E-Mail</label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <input type="email" name="email" id="email" placeholder="example@gmail.com"
                                class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                                required>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                            <input type="password" id="password" name="password"
                                placeholder="••••••••••••••••••••••••••"
                                class="w-full pl-12 pr-12 py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                                required>
                            <button type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition duration-200 shadow-lg shadow-blue-500/50">
                        Sign In
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center my-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink mx-4 text-gray-500 font-medium">atau</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- Sign In Link -->
                    <div class="text-center">
                        <p class="text-gray-600">
                            Belum punya akun?
                            <a href="/register" class="text-blue-600 font-semibold hover:text-blue-700">Daftar
                                Sekarang</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login/index.js') }}"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#000DFB'
            });
        </script>
    @endif
</body>
</html>
