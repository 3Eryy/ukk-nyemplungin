@extends('layouts.user.index')

@section('title', 'Pilih Perlengkapan')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('user.dashboard') }}" class="hover:text-[#000DFB] transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 font-medium">Pilih Perlengkapan</span>
        </div>
        <h1 class="text-2xl font-extrabold text-black">Pilih Perlengkapan</h1>
        <p class="text-sm text-gray-500 mt-1">Temukan alat yang kamu butuhkan dan tambahkan ke keranjang</p>
    </div>

    {{-- Categories --}}
    @foreach($categories as $category)
        @if($category->equipments->isNotEmpty())
            <div class="mb-12">

                {{-- Category Header --}}
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-1 h-6 rounded-full bg-[#000DFB]"></div>
                    <h2 class="text-lg font-extrabold text-black">{{ $category->name }}</h2>
                    <span class="text-xs font-semibold text-[#000DFB] bg-[#000DFB]/10 px-2.5 py-0.5 rounded-full">
                        {{ $category->equipments->count() }} item
                    </span>
                </div>
                @if($category->description)
                    <p class="text-sm text-gray-400 ml-4 mb-5">{{ $category->description }}</p>
                @else
                    <div class="mb-5"></div>
                @endif

                {{-- Equipment Grid — marketplace style --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($category->equipments as $equipment)
                        @php $outOfStock = $equipment->stock < 1; @endphp

                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col transition-all duration-200
                            {{ $outOfStock ? 'opacity-60' : 'hover:shadow-lg hover:-translate-y-1' }}">

                            {{-- Image Area: square aspect ratio --}}
                            <div class="relative w-full overflow-hidden bg-gray-50" style="aspect-ratio: 1/1;">
                                @if($equipment->image)
                                    <img src="{{ $equipment->image }}"
                                         alt="{{ $equipment->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 {{ !$outOfStock ? 'hover:scale-105' : '' }}">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                                        <i class="fas fa-camera text-gray-300 text-3xl mb-1"></i>
                                        <span style="font-size:10px" class="text-gray-300 font-medium tracking-wide uppercase">No Photo</span>
                                    </div>
                                @endif

                                {{-- Out of stock overlay --}}
                                @if($outOfStock)
                                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                        <span class="bg-red-500 text-white font-bold px-3 py-1 rounded-full shadow" style="font-size:11px">Habis</span>
                                    </div>
                                @else
                                    {{-- Stock pill bottom-left --}}
                                    <div class="absolute bottom-2 left-2">
                                        <span class="inline-flex items-center space-x-1 bg-black/55 text-white font-semibold px-2 py-0.5 rounded-full" style="font-size:10px">
                                            <i class="fas fa-layer-group" style="font-size:8px"></i>
                                            <span>Stok: {{ $equipment->stock }}</span>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-3 flex flex-col flex-1">

                                {{-- Name, clamped to 2 lines --}}
                                <h3 class="font-bold text-gray-900 leading-snug mb-1"
                                    style="font-size:13px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $equipment->name }}
                                </h3>

                                {{-- Price --}}
                                <div class="mt-auto pt-2">
                                    <p class="font-extrabold text-[#000DFB] leading-tight" style="font-size:14px">
                                        Rp {{ number_format($equipment->hourly_price, 0) }}
                                    </p>
                                    <p class="text-gray-400 font-medium" style="font-size:11px; margin-top:-1px">per jam</p>
                                </div>

                                {{-- Add to Cart --}}
                                <form action="{{ route('user.cart.add', $equipment->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @if($outOfStock)
                                        <button type="button" disabled
                                                class="w-full font-semibold bg-gray-100 text-gray-400 py-2 rounded-xl cursor-not-allowed"
                                                style="font-size:12px">
                                            Stok Habis
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="w-full font-bold bg-[#000DFB] hover:bg-[#0009CC] active:scale-95 text-white py-2 rounded-xl transition-all duration-150 shadow-sm flex items-center justify-center space-x-1.5"
                                                style="font-size:12px">
                                            <i class="fas fa-cart-plus" style="font-size:11px"></i>
                                            <span>+ Keranjang</span>
                                        </button>
                                    @endif
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    @endforeach

    {{-- Empty State --}}
    @if($categories->every(fn($c) => $c->equipments->isEmpty()))
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#000DFB]/8 flex items-center justify-center mb-4">
                <i class="fas fa-box-open text-2xl text-[#000DFB]/40"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Perlengkapan</h3>
            <p class="text-sm text-gray-400">Perlengkapan akan segera tersedia</p>
        </div>
    @endif

</div>
@endsection