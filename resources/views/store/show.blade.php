@extends('layouts.store')

@section('title', $product->name . ' - ladydevice.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition-colors">Toko</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('home', ['category' => $product->category->slug]) }}" class="hover:text-rose-600 transition-colors">{{ $product->category->name }}</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-700 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Card -->
    <div class="bg-white rounded-3xl border border-rose-100 p-6 sm:p-10 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Image Gallery -->
        <div class="lg:col-span-6 flex flex-col items-center justify-center bg-slate-50 rounded-3xl p-6 sm:p-10 border border-slate-100 relative">
            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=800&q=80' }}" 
                 alt="{{ $product->name }}" 
                 class="max-h-96 w-auto object-contain rounded-2xl shadow-lg">

            @if($product->color)
                <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-700 shadow-sm border border-slate-200">
                    🌸 Warna Estetik: {{ $product->color }}
                </div>
            @endif
        </div>

        <!-- Right: Information & Cart Form -->
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">
                        {{ $product->category->name }}
                    </span>
                    <span class="text-xs font-mono text-slate-400">SKU: {{ $product->sku }}</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-serif leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- Price -->
                <div class="p-4 rounded-2xl bg-rose-50/50 border border-rose-100 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] text-slate-500 uppercase font-semibold">Harga Resmi Toko</div>
                        <div class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        @if($product->stock > $product->minimum_stock)
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-full border border-emerald-200 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Ready Stock ({{ $product->stock }} unit)
                            </span>
                        @elseif($product->stock > 0)
                            <span class="bg-rose-100 text-rose-800 text-xs font-bold px-3 py-1.5 rounded-full border border-rose-300 flex items-center gap-1.5 animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Stok Menipis ({{ $product->stock }} unit tersisa)
                            </span>
                        @else
                            <span class="bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $product->description ?: 'Laptop estetik performa tinggi pilihan terbaik dari kurasi ladydevice.store.' }}
                </p>

                <!-- Full Specifications Box -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-microchip text-rose-500"></i> Spesifikasi Teknis
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed font-mono">
                        {{ $product->specs ?: 'Belum ada spesifikasi tertulis.' }}
                    </p>
                </div>
            </div>

            <!-- Add to Cart or Out of Stock Alert -->
            <div class="pt-6 border-t border-slate-100">
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="w-full sm:w-36 flex items-center border border-rose-200 rounded-2xl bg-white p-1 shadow-sm">
                            <span class="px-3 text-xs text-slate-500 font-semibold">Qty:</span>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                class="w-full text-center text-sm font-bold text-slate-800 focus:outline-none">
                        </div>

                        <button type="submit" class="w-full sm:flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-sm shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bag-shopping"></i> Tambahkan ke Keranjang
                        </button>
                    </form>
                @else
                    <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-slate-600 text-xs text-center">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-base mb-1 block"></i>
                        Mohon maaf, saat ini stok seri laptop ini sedang habis. Silakan pilih seri lainnya atau nantikan restock berikutnya.
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="space-y-4">
            <h3 class="text-lg font-bold font-serif text-slate-900">Koleksi Serupa Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedProducts as $rel)
                    <a href="{{ route('store.show', $rel->slug) }}" class="p-4 rounded-2xl bg-white border border-rose-100 hover:shadow-md transition-all flex items-center gap-4">
                        <img src="{{ $rel->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=200&q=80' }}" class="w-16 h-16 object-contain rounded-xl bg-slate-50 p-1">
                        <div>
                            <div class="font-bold text-xs text-slate-800 line-clamp-1">{{ $rel->name }}</div>
                            <div class="text-[11px] font-black text-rose-600 mt-0.5">Rp {{ number_format($rel->selling_price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
