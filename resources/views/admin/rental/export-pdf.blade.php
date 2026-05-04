<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Penyewaan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            color: #555;
        }

        .date {
            margin-top: 5px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">NYEMPLUNG.IN</div>
        <div class="subtitle">Laporan Data Penyewaan Alat</div>
        <div class="date">
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Tanggal Sewa</th>
                <th>Total Rental</th>
                <th>Total Bayar</th>
                <th>Status Rental</th>
                <th>Status Pembayaran</th>
                <th>Metode</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentals as $rental)
                @php
                    $totalPayment = $rental->payments->sum('amount');
                    $lastPayment = $rental->payments->last();
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $rental->user->name ?? '-' }}</td>

                    <td>
                        @foreach ($rental->rentalItems as $item)
                            {{ $item->equipment->name ?? '-' }}<br>
                        @endforeach
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($rental->rental_start)->format('d-m-Y') }}
                        -
                        {{ \Carbon\Carbon::parse($rental->rental_end)->format('d-m-Y') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($totalPayment, 0, ',', '.') }}
                    </td>

                    <td>{{ ucfirst($rental->status) }}</td>

                    <td>
                        {{ $lastPayment->payment_status ?? 'Belum Bayar' }}
                    </td>

                    <td>
                        {{ $lastPayment->payment_method ?? '-' }}
                    </td>

                    <td>
                        {{ $lastPayment ? \Carbon\Carbon::parse($lastPayment->payment_date)->format('d-m-Y') : '-' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total Data: {{ $rentals->count() }} transaksi
    </div>

</body>

</html>
