<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penyewaan - #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .receipt-body {
            padding: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table-custom th,
        .table-custom td {
            border: 1px solid #dee2e6;
            padding: 10px;
            vertical-align: top;
        }
        
        .table-custom th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        .table-custom tfoot td {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .total-amount {
            font-size: 1.3em;
            color: #dc3545;
            font-weight: bold;
        }
        
        .alert-cod {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .footer-note {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
            font-size: 0.9em;
            color: #6c757d;
        }
        
        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .btn-print {
                display: none !important;
            }
            
            .receipt-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
            
            .receipt-header {
                background: #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .table-custom th {
                background: #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <i class="fas fa-camera fa-3x mb-3"></i>
            <h1 class="mb-2">NOTA PENYEWAAN</h1>
            <h5>Rental Equipment Pro</h5>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <!-- Info Transaksi -->
            <div class="text-center mb-4">
                <h4 class="text-primary">#{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</h4>
                <p class="mb-1"><strong>Tanggal Transaksi:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-warning text-dark px-3 py-2">Menunggu Pembayaran</span>
                </p>
            </div>
            
            <!-- Informasi Pelanggan & Sewa -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-section">
                        <h6 class="text-primary mb-3"><i class="fas fa-user"></i> Informasi Pelanggan</h6>
                        <table style="width: 100%;">
                            <tr>
                                <td width="35%"><strong>Nama</strong></td>
                                <td>: {{ $rental->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $rental->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Telepon</strong></td>
                                <td>: {{ $rental->user->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-section">
                        <h6 class="text-primary mb-3"><i class="fas fa-calendar"></i> Informasi Penyewaan</h6>
                        <table style="width: 100%;">
                            <tr>
                                <td width="40%"><strong>Tanggal Sewa</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($rental->rental_start)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kembali</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($rental->rental_end)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Lama Sewa</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($rental->rental_start)->diffInDays(\Carbon\Carbon::parse($rental->rental_end)) + 1 }} Hari</td>
                            </tr>
                            <tr>
                                <td><strong>Metode Bayar</strong></td>
                                <td>: {{ $rental->payment_method == 'cod' ? 'Bayar di Tempat (COD)' : 'Transfer Bank' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Item -->
            <h6 class="text-primary mb-3"><i class="fas fa-boxes"></i> Detail Item yang Disewa</h6>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 35%">Nama Equipment</th>
                        <th style="width: 15%">Kategori</th>
                        <th style="width: 10%">Jumlah</th>
                        <th style="width: 15%">Harga/Hari</th>
                        <th style="width: 20%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rental->rentalItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->equipment->name }}</td>
                        <td>{{ $item->equipment->category->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>TOTAL</strong></td>
                        <td class="text-end total-amount">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Informasi COD -->
            @if($rental->payment_method == 'cod')
            <div class="alert-cod">
                <h6 class="text-info mb-2"><i class="fas fa-money-bill-wave"></i> Informasi Pembayaran COD</h6>
                <p class="mb-2">Pembayaran dilakukan secara tunai saat mengambil barang. Silakan datang ke lokasi penyewaan kami dengan membawa nota ini.</p>
                <hr>
                <p class="mb-1"><strong>Alamat:</strong> Jl. Rental Equipment No. 123, Kota Anda</p>
                <p class="mb-0"><strong>Jam Operasional:</strong> Senin - Sabtu, 08:00 - 17:00</p>
            </div>
            @endif
            
            <!-- Catatan -->
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px;">
                <h6 class="text-warning mb-2"><i class="fas fa-exclamation-triangle"></i> Catatan Penting</h6>
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    <li>Harap membawa nota ini saat mengambil barang</li>
                    <li>Pastikan barang dalam kondisi baik saat pengembalian</li>
                    <li>Simpan nota ini sebagai bukti transaksi</li>
                </ul>
            </div>
            
            <!-- Footer -->
            <div class="footer-note">
                <p class="mb-1">Terima kasih telah menyewa di Nyempung.In</p>
                <small>Nota ini dibuat secara otomatis oleh sistem | Verifikasi: #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</small>
            </div>
        </div>
    </div>
    
    <!-- Tombol Print -->
    <button onclick="window.print();" class="btn btn-primary btn-print">
        <i class="fas fa-print"></i> Cetak Nota
    </button>
    
    <script>
        // Auto print jika parameter print=1
        if (window.location.search.includes('print=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        }
    </script>
</body>
</html>