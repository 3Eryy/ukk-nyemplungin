@extends('layouts.user.index')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('user.rentals.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Kembali ke Daftar Peminjaman
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Peminjaman #{{ $rental->id }}
                </h1>
                @if($rental->status == 'menunggu')
                    <form action="{{ route('user.rentals.cancel', $rental->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg transition">
                            Batalkan Peminjaman
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="p-6">
            <!-- Status -->
            <div class="mb-6">
                @php
                    $statusColors = [
                        'menunggu' => 'yellow',
                        'disetujui' => 'blue',
                        'sedang berlangsung' => 'green',
                        'selesai' => 'gray',
                        'dibatalkan' => 'red',
                    ];
                    $statusColor = $statusColors[$rental->status] ?? 'gray';
                @endphp
                <div class="inline-flex items-center px-3 py-2 rounded-full text-sm font-semibold bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                    Status: {{ ucfirst($rental->status) }}
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Peminjaman</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium text-gray-600">Tanggal Mulai:</span> {{ \Carbon\Carbon::parse($rental->rental_start)->format('d/m/Y H:i') }}</p>
                        <p><span class="font-medium text-gray-600">Tanggal Selesai:</span> {{ \Carbon\Carbon::parse($rental->rental_end)->format('d/m/Y H:i') }}</p>
                        <p><span class="font-medium text-gray-600">Total Hari:</span> {{ \Carbon\Carbon::parse($rental->rental_start)->diffInDays($rental->rental_end) + 1 }} hari</p>
                        <p><span class="font-medium text-gray-600">Total Harga:</span> <span class="text-xl font-bold text-green-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span></p>
                        @if($rental->notes)
                            <p><span class="font-medium text-gray-600">Catatan:</span> {{ $rental->notes }}</p>
                        @endif
                    </div>
                </div>

                @if(isset($payment) && $payment)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pembayaran</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium text-gray-600">Status Pembayaran:</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $payment->status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </p>
                        <p><span class="font-medium text-gray-600">Metode Pembayaran:</span> {{ ucfirst($payment->payment_method) ?? '-' }}</p>
                        <p><span class="font-medium text-gray-600">Tanggal Pembayaran:</span> {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Daftar Alat yang Disewa -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Daftar Alat yang Disewa</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rental->rentalItems as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->equipment->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800">Total:</td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection