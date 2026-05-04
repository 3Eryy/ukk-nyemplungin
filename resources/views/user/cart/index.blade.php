@extends('layouts.user.index')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('user.rentals.create') }}" class="hover:text-[#000DFB] transition-colors">Perlengkapan</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 font-medium">Keranjang</span>
        </div>
        <h1 class="text-2xl font-extrabold text-black">Keranjang Belanja</h1>
    </div>

    @if($cartItems->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-[#000DFB]/8 flex items-center justify-center mb-4">
                <i class="fas fa-shopping-cart text-3xl text-[#000DFB]/40"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-1">Keranjang Kosong</h3>
            <p class="text-sm text-gray-400 mb-6">Belum ada perlengkapan yang ditambahkan</p>
            <a href="{{ route('user.rentals.create') }}"
               class="inline-flex items-center space-x-2 bg-[#000DFB] hover:bg-[#0009CC] text-white font-bold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-[#000DFB]/25 text-sm">
                <i class="fas fa-plus text-xs"></i>
                <span>Mulai Belanja</span>
            </a>
        </div>

    @else

    <form action="{{ route('user.cart.checkout') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="payment_method" id="payment_method_input" value="transfer">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ===== CART ITEMS (left 2/3) ===== --}}
            <div class="lg:col-span-2 space-y-4">

                @foreach($cartItems as $item)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">

                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        @if($item->equipment->image)
                            <img src="{{ $item->equipment->image }}"
                                 alt="{{ $item->equipment->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-camera text-gray-300 text-xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug truncate">{{ $item->equipment->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Rp {{ number_format($item->equipment->hourly_price, 0) }} / jam</p>

                        {{-- Quantity controls --}}
                        <div class="flex items-center gap-2 mt-3">
                            <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                    <span class="px-2 py-1 text-xs text-gray-500 bg-gray-50">Qty</span>
                                    <input type="number"
                                           name="quantity"
                                           value="{{ $item->quantity }}"
                                           min="1"
                                           max="{{ $item->equipment->stock }}"
                                           class="w-14 text-center text-sm font-semibold border-0 border-l border-gray-200 px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#000DFB] focus:ring-inset">
                                </div>
                                <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-[#000DFB]/10 hover:bg-[#000DFB]/20 text-[#000DFB] flex items-center justify-center transition-colors"
                                        title="Update">
                                    <i class="fas fa-sync-alt" style="font-size:11px"></i>
                                </button>
                            </form>

                            <form action="{{ route('user.cart.remove', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition-colors"
                                        title="Hapus">
                                    <i class="fas fa-trash" style="font-size:11px"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right flex-shrink-0">
                        <p class="font-extrabold text-gray-900 text-sm">
                            Rp {{ number_format($item->equipment->hourly_price * $item->quantity, 0) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $item->quantity }} × /hari</p>
                    </div>

                </div>
                @endforeach

                {{-- Bottom actions --}}
                <div class="flex items-center justify-between pt-1">
                    <form action="{{ route('user.cart.clear') }}" method="POST"
                          onsubmit="return confirm('Kosongkan semua keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center space-x-1.5 text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">
                            <i class="fas fa-trash"></i>
                            <span>Kosongkan Keranjang</span>
                        </button>
                    </form>
                    <a href="{{ route('user.rentals.create') }}"
                       class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#000DFB] hover:text-[#0009CC] transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Barang</span>
                    </a>
                </div>

            </div>

            {{-- ===== CHECKOUT PANEL (right 1/3) ===== --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Tanggal Sewa --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Tanggal Sewa</p>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Mulai</label>
                            <input type="datetime-local"
                                   name="rental_start"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#000DFB]/30 focus:border-[#000DFB] transition"
                                   required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Selesai</label>
                            <input type="datetime-local"
                                   name="rental_end"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#000DFB]/30 focus:border-[#000DFB] transition"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Metode Pembayaran</p>

                    <div class="space-y-3" id="payment-options">

                        {{-- Transfer --}}
                        <label id="label-transfer"
                               class="flex items-center gap-3 p-3 rounded-xl border-2 border-[#000DFB] bg-[#000DFB]/5 cursor-pointer transition-all duration-150">
                            <input type="radio" name="_payment_method" value="transfer"
                                   class="hidden" checked onchange="selectPayment('transfer')">
                            <div class="w-9 h-9 rounded-lg bg-[#000DFB] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-university text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900">Transfer Bank</p>
                                <p class="text-xs text-gray-400 mt-0.5">Bayar via Midtrans (VA, QRIS, dll)</p>
                            </div>
                            <div id="check-transfer" class="w-5 h-5 rounded-full bg-[#000DFB] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white" style="font-size:9px"></i>
                            </div>
                        </label>

                        {{-- COD --}}
                        <label id="label-cod"
                               class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 bg-white cursor-pointer transition-all duration-150">
                            <input type="radio" name="_payment_method" value="cod"
                                   class="hidden" onchange="selectPayment('cod')">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-gray-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900">Bayar di Tempat</p>
                                <p class="text-xs text-gray-400 mt-0.5">COD — struk dicetak langsung</p>
                            </div>
                            <div id="check-cod" class="w-5 h-5 rounded-full border-2 border-gray-200 flex-shrink-0"></div>
                        </label>

                    </div>

                    {{-- Transfer info box --}}
                    <div id="transfer-info" class="mt-3 flex items-start gap-2 bg-blue-50 border border-blue-200 rounded-xl px-3 py-2.5">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5" style="font-size:13px; flex-shrink:0"></i>
                        <p class="text-xs text-blue-700 font-medium leading-relaxed">
                            Anda akan diarahkan ke halaman pembayaran Midtrans setelah konfirmasi pesanan.
                        </p>
                    </div>

                    {{-- COD info box (hidden by default) --}}
                    <div id="cod-info" class="hidden mt-3 flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5">
                        <i class="fas fa-receipt text-amber-500 mt-0.5" style="font-size:13px; flex-shrink:0"></i>
                        <p class="text-xs text-amber-700 font-medium leading-relaxed">
                            Nota/struk akan otomatis dicetak setelah pemesanan dikonfirmasi. Bayar langsung kepada petugas saat pengambilan.
                        </p>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ringkasan</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Item</span>
                            <span class="font-semibold text-gray-800">{{ $cartItems->sum('quantity') }} item</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Total / hari</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-100 my-3"></div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Bayar</span>
                        <span class="text-lg font-extrabold text-[#000DFB]">Rp {{ number_format($total, 0) }}</span>
                    </div>
                </div>

                {{-- Checkout Button --}}
                <button type="submit" id="checkout-btn"
                        class="w-full flex items-center justify-center space-x-2 bg-[#000DFB] hover:bg-[#0009CC] active:scale-95 text-white font-bold py-3.5 rounded-2xl transition-all duration-200 shadow-lg shadow-[#000DFB]/30 hover:shadow-[#000DFB]/50 hover:-translate-y-0.5">
                    <i class="fas fa-credit-card text-sm" id="checkout-btn-icon"></i>
                    <span id="checkout-btn-text">Lanjut ke Pembayaran</span>
                </button>

                <p class="text-center text-xs text-gray-400 flex items-center justify-center space-x-1.5">
                    <i class="fas fa-shield-alt text-green-400"></i>
                    <span>Transaksi aman & terenkripsi</span>
                </p>

            </div>

        </div>
    </form>

    @endif
</div>

<script>
function selectPayment(method) {
    const labelTransfer = document.getElementById('label-transfer');
    const labelCod      = document.getElementById('label-cod');
    const checkTransfer = document.getElementById('check-transfer');
    const checkCod      = document.getElementById('check-cod');
    const codInfo       = document.getElementById('cod-info');
    const transferInfo  = document.getElementById('transfer-info');
    const btnText       = document.getElementById('checkout-btn-text');
    const btnIcon       = document.getElementById('checkout-btn-icon');
    const input         = document.getElementById('payment_method_input');

    if (method === 'transfer') {
        // Active styles: transfer
        labelTransfer.className = 'flex items-center gap-3 p-3 rounded-xl border-2 border-[#000DFB] bg-[#000DFB]/5 cursor-pointer transition-all duration-150';
        labelCod.className      = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 bg-white cursor-pointer transition-all duration-150';

        // Icon boxes
        labelTransfer.querySelector('.w-9').className = 'w-9 h-9 rounded-lg bg-[#000DFB] flex items-center justify-center flex-shrink-0';
        labelTransfer.querySelector('.w-9 i').className = 'fas fa-university text-white text-sm';
        labelCod.querySelector('.w-9').className = 'w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0';
        labelCod.querySelector('.w-9 i').className = 'fas fa-money-bill-wave text-gray-500 text-sm';

        // Check indicators
        checkTransfer.className = 'w-5 h-5 rounded-full bg-[#000DFB] flex items-center justify-center flex-shrink-0';
        checkTransfer.innerHTML = '<i class="fas fa-check text-white" style="font-size:9px"></i>';
        checkCod.className = 'w-5 h-5 rounded-full border-2 border-gray-200 flex-shrink-0';
        checkCod.innerHTML = '';

        // Info boxes
        transferInfo.classList.remove('hidden');
        codInfo.classList.add('hidden');

        // Button
        btnText.textContent = 'Lanjut ke Pembayaran';
        btnIcon.className = 'fas fa-credit-card text-sm';
        input.value = 'transfer';

    } else {
        // Active styles: cod
        labelCod.className      = 'flex items-center gap-3 p-3 rounded-xl border-2 border-[#000DFB] bg-[#000DFB]/5 cursor-pointer transition-all duration-150';
        labelTransfer.className = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 bg-white cursor-pointer transition-all duration-150';

        // Icon boxes
        labelCod.querySelector('.w-9').className = 'w-9 h-9 rounded-lg bg-[#000DFB] flex items-center justify-center flex-shrink-0';
        labelCod.querySelector('.w-9 i').className = 'fas fa-money-bill-wave text-white text-sm';
        labelTransfer.querySelector('.w-9').className = 'w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0';
        labelTransfer.querySelector('.w-9 i').className = 'fas fa-university text-gray-500 text-sm';

        // Check indicators
        checkCod.className = 'w-5 h-5 rounded-full bg-[#000DFB] flex items-center justify-center flex-shrink-0';
        checkCod.innerHTML = '<i class="fas fa-check text-white" style="font-size:9px"></i>';
        checkTransfer.className = 'w-5 h-5 rounded-full border-2 border-gray-200 flex-shrink-0';
        checkTransfer.innerHTML = '';

        // Info boxes
        codInfo.classList.remove('hidden');
        transferInfo.classList.add('hidden');

        // Button
        btnText.textContent = 'Pesan & Cetak Struk';
        btnIcon.className = 'fas fa-receipt text-sm';
        input.value = 'cod';
    }
}
</script>
@endsection