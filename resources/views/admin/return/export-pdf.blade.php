<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead th {
            background-color: #F9F8F6;
            color: #333;
            font-weight: bold;
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table tbody td {
            padding: 8px;
            border: 1px solid #ddd;
            color: #333;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: right;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-baik {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-rusak-ringan {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-rusak-berat {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PENGEMBALIAN BARANG NYEMPLUNG.IN</h1>
        <p>Tanggal Export: {{ date('d-m-Y H:i:s') }}</p>
        <hr style="border: 1px solid #ddd; margin: 10px 0;">
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">Nama Peminjam</th>
                <th width="20%">Nama Barang</th>
                <th width="12%">Tgl Dipinjam</th>
                <th width="12%">Tgl Kembali</th>
                <th width="12%">Total Harga</th>
                <th width="12%">Kondisi</th>
                <th width="12%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $index => $return)
                <tr>
                    <td class="text-center">{{ $startNumber + $index }}</td>
                    <td>{{ $return->rental->user->name ?? '-' }}</td>
                    <td>
                        @php
                            $equipments = $return->rental->rentalItems
                                ->map(function ($ri) {
                                    return $ri->equipment->name ?? '-';
                                })
                                ->join(', ');
                        @endphp
                        {{ $equipments }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($return->rental->rental_start)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d-m-Y') }}</td>
                    <td>Rp. {{ number_format($return->rental->total_price, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $condition = $return->condition ?? '-';
                            $badgeClass = '';
                            if ($condition == 'Baik') {
                                $badgeClass = 'badge-baik';
                            } elseif ($condition == 'Rusak Ringan') {
                                $badgeClass = 'badge-rusak-ringan';
                            } elseif ($condition == 'Rusak Berat') {
                                $badgeClass = 'badge-rusak-berat';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $condition }}</span>
                    </td>
                    <td>{{ $return->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        Tidak ada data pengembalian ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(count($returns) > 0)
    <div style="margin-top: 20px;">
        <table style="width: 40%; float: right; border: none;">
            <tr>
                <td style="border: none;"><strong>Total Pengembalian:</strong></td>
                <td style="border: none;">{{ count($returns) }} data</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Pendapatan:</strong></td>
                <td style="border: none;">
                    Rp. {{ number_format($returns->sum('rental.total_price'), 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        <p>Halaman ini dicetak dari sistem peminjaman barang</p>
    </div>
</body>
</html>