@extends('layouts.user.index')

@section('title', 'Pembayaran Rental #' . $rental->id)

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('user.rentals.index') }}" class="hover:text-[#000DFB] transition-colors">Peminjaman</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">Pembayaran</span>
        </div>
        <h1 class="text-2xl font-extrabold text-black">Pembayaran Rental</h1>
        <p class="text-sm text-gray-500 mt-1">Order <span class="font-semibold text-[#000DFB]">#{{ $rental->id }}</span></p>
    </div>

    {{-- Pending Payment Alert --}}
    @if($payment)
        <div class="mb-5 flex items-start space-x-3 bg-blue-50 border border-[#000DFB]/20 px-5 py-4 rounded-2xl">
            <div class="w-8 h-8 rounded-lg bg-[#000DFB] flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-clock text-white text-xs"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Pembayaran Pending</p>
                <p class="text-xs text-gray-500 mt-0.5">Order ID: <span class="font-mono font-semibold text-[#000DFB]">{{ $payment->order_id }}</span></p>
                <span class="inline-block mt-1.5 text-xs font-semibold bg-yellow-100 text-yellow-700 px-2.5 py-0.5 rounded-full">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>
    @endif

    {{-- Payment Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Card Header --}}
        <div class="bg-[#000DFB] px-6 py-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                    <i class="fas fa-receipt text-white"></i>
                </div>
                <div>
                    <p class="text-white/70 text-xs font-medium">Rental</p>
                    <p class="text-white font-bold text-lg leading-tight">#{{ $rental->id }}</p>
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="px-6 py-6 space-y-6">

            {{-- Item Detail --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Rincian Item</p>
                <div class="space-y-2">
                    @foreach($rental->rentalItems as $item)
                        <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-[#000DFB]/10 flex items-center justify-center">
                                    <i class="fas fa-box text-[#000DFB] text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->equipment->name ?? 'Item' }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->quantity }}x &times; Rp {{ number_format($item->price) }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($item->price * $item->quantity) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Total --}}
            <div class="bg-gray-50 rounded-2xl px-5 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Pembayaran</p>
                    <p class="text-2xl font-extrabold text-black mt-0.5">
                        Rp {{ number_format($rental->rentalItems->sum(fn($item) => $item->price * $item->quantity)) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#000DFB]/10 flex items-center justify-center">
                    <i class="fas fa-wallet text-[#000DFB] text-lg"></i>
                </div>
            </div>

            {{-- Pay Button --}}
            <button
                id="pay-button"
                class="w-full bg-[#000DFB] hover:bg-[#0009CC] active:bg-[#0007AA] text-white font-bold py-4 rounded-2xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-[#000DFB]/30 hover:shadow-[#000DFB]/50 hover:-translate-y-0.5 active:translate-y-0"
            >
                <i class="fas fa-lock text-sm opacity-80"></i>
                <span>Bayar Sekarang</span>
            </button>

            {{-- Security Note --}}
            <p class="text-center text-xs text-gray-400 flex items-center justify-center space-x-1.5">
                <i class="fas fa-shield-alt text-green-400"></i>
                <span>Pembayaran diproses secara aman melalui Midtrans</span>
            </p>

        </div>
    </div>

</div>

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').onclick = function () {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm opacity-80"></i><span class="ml-2">Memproses...</span>';

    fetch('{{ route("payments.create", $rental->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock text-sm opacity-80"></i><span>Bayar Sekarang</span>';

        if (data.snap_token) {
            window.snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    window.location.href = '{{ route("payments.finish") }}?order_id=' + data.order_id;
                },
                onPending: function (result) {
                    window.location.href = '{{ route("payments.unfinish") }}';
                },
                onError: function (result) {
                    window.location.href = '{{ route("payments.error") }}';
                },
                onClose: function () {
                    alert('Anda menutup popup pembayaran');
                }
            });
        } else {
            alert('Gagal membuat transaksi: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock text-sm opacity-80"></i><span>Bayar Sekarang</span>';
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
};
</script>

@endsection