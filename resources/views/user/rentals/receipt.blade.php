@extends('layouts.user.index')

@section('title', 'Nota Penyewaan')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Tombol Aksi -->
                <div class="mb-3 text-end no-print">
                    <a href="{{ route('user.rentals.receipt', ['rental' => $rental->id, 'print' => 1]) }}" target="_blank"
                        class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> Cetak / Download PDF
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>
                </div>

                <!-- Kartu Nota -->
                <div class="card shadow receipt-wrapper" id="receipt-card">
                    <div class="card-body p-0">
                        <!-- Konten Nota -->
                        <div id="receipt-content" class="p-4 p-md-5">
                            <!-- Header Nota -->
                            <div class="text-center mb-4">
                                <h2 class="mb-2 fw-bold" style="color: #00000; font-weight: bold;">NOTA PENYEWAAN</h2>
                                <h5 class="text-muted mb-3">Nyemplung.In</h5>
                                <div class="border-top border-bottom py-2 my-3">
                                    <p class="mb-1"><strong class="text-primary">No. Transaksi:</strong> <span
                                            class="fw-bold">#{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                                    <p class="mb-1"><strong>Tanggal Transaksi:</strong> {{ now()->format('d/m/Y H:i:s') }}
                                    </p>
                                    <p class="mb-0"><strong>Status:</strong>
                                        <span class="badge bg-warning text-dark px-3 py-2">Menunggu Pembayaran</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Informasi Pelanggan & Penyewaan -->
                            <div class="row mb-4 g-4">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <h5 class="border-bottom pb-2 mb-3 fw-bold text-primary">
                                            <i class="fas fa-user"></i> Informasi Pelanggan
                                        </h5>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td width="35%" class="ps-0"><strong>Nama Lengkap</strong></td>
                                                <td>: {{ $rental->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-0"><strong>Email</strong></td>
                                                <td>: {{ $rental->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-0"><strong>No. Telepon</strong></td>
                                                <td>: {{ $rental->user->phone ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <h5 class="border-bottom pb-2 mb-3 fw-bold text-primary">
                                            <i class="fas fa-calendar-alt"></i> Informasi Penyewaan
                                        </h5>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td width="40%" class="ps-0"><strong>Tanggal Sewa</strong></td>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($rental->rental_start)->translatedFormat('l, d F Y H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-0"><strong>Tanggal Kembali</strong></td>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($rental->rental_end)->translatedFormat('l, d F Y H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-0"><strong>Lama Sewa</strong></td>
                                                <td>: <span
                                                        class="badge bg-success">{{ \Carbon\Carbon::parse($rental->rental_start)->diffInDays(\Carbon\Carbon::parse($rental->rental_end)) + 1 }}
                                                        Hari</span></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-0"><strong>Metode Bayar</strong></td>
                                                <td>:
                                                    @if ($rental->payment_method == 'cod')
                                                        <span class="badge bg-info">Bayar di Tempat (COD)</span>
                                                    @else
                                                        <span class="badge bg-primary">Transfer Bank</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Item yang Disewa -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2 mb-3 fw-bold text-primary">
                                    <i class="fas fa-boxes"></i> Detail Item yang Disewa
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead class="table-primary">
                                            <tr class="text-center">
                                                <th style="width: 5%">#</th>
                                                <th style="width: 35%">Nama Equipment</th>
                                                <th style="width: 15%">Kategori</th>
                                                <th style="width: 10%">Jumlah</th>
                                                <th style="width: 15%">Harga/Hari</th>
                                                <th style="width: 20%">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rental->rentalItems as $index => $item)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $item->equipment->name }}</td>
                                                    <td>{{ $item->equipment->category->name ?? '-' }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-end fw-bold">Rp
                                                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-secondary fw-bold">
                                                <td colspan="5" class="text-end">Total Keseluruhan</td>
                                                <td class="text-end text-danger h5">Rp
                                                    {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Informasi Pembayaran (khusus COD) -->
                            @if ($rental->payment_method == 'cod')
                                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                                    <h5 class="alert-heading fw-bold">
                                        <i class="fas fa-money-bill-wave"></i> Informasi Pembayaran COD
                                    </h5>
                                    <p class="mb-2">Pembayaran dilakukan secara tunai saat mengambil barang. Silakan
                                        datang ke lokasi penyewaan kami dengan membawa nota ini.</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="fas fa-map-marker-alt"></i> <strong>Alamat Pengambilan:</strong><br>
                                            Jl. Rental Equipment No. 123, Kota Anda
                                        </div>
                                        <div class="col-md-6">
                                            <i class="fas fa-clock"></i> <strong>Jam Operasional:</strong><br>
                                            Senin - Sabtu, 08:00 - 17:00
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Catatan Penting -->
                            <div class="mt-4">
                                <div class="bg-warning bg-opacity-10 p-3 rounded border border-warning">
                                    <h5 class="border-bottom pb-2 mb-3 fw-bold text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Catatan Penting
                                    </h5>
                                    <ul class="mb-0" style="padding-left: 1.2rem;">
                                        <li class="mb-1">Harap membawa nota ini saat mengambil barang</li>
                                        <li class="mb-1">Barang yang sudah disewa tidak dapat dikembalikan sebelum waktu
                                            yang ditentukan</li>
                                        <li class="mb-1">Pastikan barang dalam kondisi baik saat pengembalian</li>
                                        <li>Simpan nota ini sebagai bukti transaksi</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="mb-1 fw-bold">Terima kasih telah menyewa di Nyempung.In</p>
                                <p class="small text-muted mb-0">Nota ini dibuat secara otomatis oleh sistem | <i
                                        class="fas fa-qrcode"></i> Verifikasi:
                                    #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styling Umum */
        .receipt-wrapper {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        #receipt-content {
            background: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-primary {
            background-color: #cfe2ff !important;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }

        /* Styling untuk Print */
        @media print {

            /* Sembunyikan semua elemen yang tidak diperlukan saat print */
            .no-print,
            .no-print *,
            .btn,
            .text-end,
            .mb-3.text-end,
            nav.navbar,
            footer,
            .sidebar,
            header {
                display: none !important;
                visibility: hidden !important;
            }

            /* Reset margin dan padding body */
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /* Container utama */
            .container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Hanya tampilkan card nota */
            .receipt-wrapper {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card-body {
                padding: 0 !important;
            }

            #receipt-content {
                padding: 15px !important;
                margin: 0 !important;
                background: white !important;
            }

            /* Warna untuk print */
            .badge {
                border: 1px solid #000 !important;
                background-color: #f0f0f0 !important;
                color: #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .table-primary {
                background-color: #e0e0e0 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .bg-light {
                background-color: #f8f9fa !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .text-primary {
                color: #000 !important;
            }

            .text-danger {
                color: #000 !important;
            }

            /* Halaman print */
            @page {
                size: A4;
                margin: 1.5cm;
            }

            /* Hindari halaman terpotong */
            .receipt-wrapper {
                page-break-inside: avoid;
            }

            /* Pastikan tabel tidak terpotong */
            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
            }
        }
    </style>

    <script>
        function printReceipt() {
            // Sembunyikan tombol dan elemen yang tidak perlu
            var originalTitle = document.title;
            document.title = 'Nota Penyewaan - #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}';

            // Panggil fungsi print browser
            window.print();

            // Kembalikan judul setelah print selesai
            setTimeout(function() {
                document.title = originalTitle;
            }, 500);
        }

        // Tambahkan event listener untuk print
        window.onbeforeprint = function() {
            // Tambahkan class khusus saat print
            document.body.classList.add('printing');
        };

        window.onafterprint = function() {
            // Hapus class setelah print selesai
            document.body.classList.remove('printing');
        };
    </script>

    <!-- Tambahan CSS untuk menyembunyikan navbar/sidebar saat print -->
    <style media="print">
        /* Sembunyikan navbar, sidebar, footer */
        nav.navbar,
        .navbar,
        .sidebar,
        footer,
        .footer,
        header:not(#receipt-content header),
        [class*="sidebar"],
        [class*="navbar"],
        [class*="footer"] {
            display: none !important;
        }

        /* Pastikan body tidak ada margin/padding berlebih */
        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Main content */
        .main-content,
        .content-wrapper,
        [class*="content"] {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Layout utama */
        .container,
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        .row {
            margin: 0 !important;
        }

        .col-md-10,
        .col-12,
        [class*="col-"] {
            padding: 0 !important;
        }
    </style>
@endsection
