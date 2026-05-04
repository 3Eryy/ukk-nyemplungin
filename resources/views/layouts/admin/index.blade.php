<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyemplung.in Admin</title>
    
    <!-- Import Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- PENTING: Alpine.js untuk fitur Buka/Tutup Sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nyemplung: {
                            blue: '#000DFB',
                            black: '#1E1E1E',
                            white: '#FFFFFF',
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
<body class="bg-gray-50 font-sans antialiased">
    <div x-data="{ open: true }" class="flex h-screen w-full overflow-hidden">

        <!-- 1. SIDEBAR (Component) -->
        <!-- Pastikan file component ini HANYA berisi div sidebar, TANPA tag html/body -->
        @include('components.admin.sidebar.index')


        <!-- 2. MAIN CONTENT -->
        <!-- flex-1: Mengisi sisa ruang kosong secara otomatis -->
        <main class="flex-1 h-screen overflow-y-auto bg-gray-50">
            @yield('content')
        </main>

    </div>

</body>
</html>