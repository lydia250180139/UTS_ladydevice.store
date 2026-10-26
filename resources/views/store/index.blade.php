@extends('layouts.store')

@section('title', 'ladydevice.store - Toko Laptop Estetik & Kekinian')

@section('content')
<div class="space-y-12 pb-16">

    <!-- 1. Hero Aesthetic Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-rose-900 via-pink-900 to-purple-950 text-white p-8 sm:p-14 lg:p-16 shadow-2xl">
            <!-- Background glow shapes -->
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-white/10 backdrop-blur-md border border-white/20 text-rose-200">
                        <i class="fa-solid fa-sparkles text-amber-300"></i> New Season Collection 2026
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black font-serif leading-tight">
                        Laptop Estetik untuk <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-rose-200 via-pink-100 to-purple-200 bg-clip-text text-transparent italic">Perempuan Berdaya.</span>
                    </h1>
                    <p class="text-sm sm:text-base text-rose-100/80 max-w-xl font-light leading-relaxed">
                        Temukan paduan sempurna antara keindahan desain minimalis pastel dan performa komputasi profesional. Dilengkapi garansi resmi dan kemudahan transaksi.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="#katalog" class="px-6 py-3.5 rounded-2xl bg-white text-rose-900 font-bold text-xs uppercase tracking-wider hover:bg-rose-50 shadow-lg shadow-rose-950/40 transition-all transform hover:-translate-y-0.5">
                            Jelajahi Koleksi Laptop &darr;
                        </a>
                        <a href="{{ route('home', ['category' => 'ultrabook-slim']) }}" class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition-all">
                            Seri Ultrabook Pastel
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 hidden lg:flex justify-center">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=700&q=80" 
                             alt="LadyDevice Featured Laptop" 
                             class="w-80 h-72 object-cover rounded-3xl shadow-2xl border-4 border-white/20 transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                        <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur-md text-slate-800 p-3 rounded-2xl shadow-xl border border-rose-100 text-xs flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="font-bold">ASUS Zenbook Lilac Mist</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Category Filter Pills -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Pilih Berdasarkan Kategori</h2>
            @if(request('category') || request('q') || request('sort'))
                <a href="{{ route('home') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('home', array_merge(request()->except(['category', 'page']))) }}" 
               class="flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ !request('category') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'bg-white border border-rose-100 text-slate-700 hover:border-rose-300' }}">
                <i class="fa-solid fa-border-all mr-1.5"></i> Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', array_merge(request()->except(['page']), ['category' => $cat->slug])) }}" 
                   class="flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 {{ request('category') === $cat->slug ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'bg-white border border-rose-100 text-slate-700 hover:border-rose-300' }}">
                    <i class="fa-solid {{ $cat->icon ?? 'fa-laptop' }} text-rose-400"></i>
                    <span>{{ $cat->name }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('category') === $cat->slug ? 'bg-white/30 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $cat->products_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 3. Catalog Section -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter and Sort Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-rose-100">
            <div>
                <h2 class="text-2xl font-bold font-serif text-slate-900">
                    Koleksi Laptop Unggulan
                </h2>
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $products->total() }} pilihan laptop estetik bergaransi resmi
                </p>
            </div>

            <!-- Sorting Dropdown -->
            <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <label for="sort" class="text-xs text-slate-400 whitespace-nowrap">Urutkan:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white border border-rose-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-400 shadow-sm">
                    <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Pilihan Unggulan</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Stok Terbanyak</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            @forelse($products as $prod)
                <div class="bg-white rounded-3xl border border-rose-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group relative">
                    
                    <!-- Product Top Tags -->
                    <div class="relative overflow-hidden bg-slate-50 aspect-video flex items-center justify-center p-4">
                        <img src="{{ $prod->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=600&q=80' }}" 
                             alt="{{ $prod->name }}" 
                             class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-500">

                        <!-- Color Badge -->
                        @if($prod->color)
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-bold text-slate-700 shadow-sm border border-slate-200/60">
                                🌸 {{ $prod->color }}
                            </div>
                        @endif

                        <!-- Stock Badge (Tersedia / Menipis / Habis) -->
                        <div class="absolute top-3 right-3">
                            @if($prod->stock > $prod->minimum_stock)
                                <span class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Tersedia ({{ $prod->stock }})
                                </span>
                            @elseif($prod->stock > 0)
                                <span class="bg-rose-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1 animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Stok Menipis ({{ $prod->stock }})
                                </span>
                            @else
                                <span class="bg-slate-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="fa-solid fa-ban text-[9px]"></i> Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        
                        <div>
                            <!-- Category and SKU -->
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                                <span class="uppercase tracking-wider font-semibold text-rose-500">{{ $prod->category->name }}</span>
                                <span class="font-mono">{{ $prod->sku }}</span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-bold text-base text-slate-900 group-hover:text-rose-600 transition-colors line-clamp-1">
                                <a href="{{ route('store.show', $prod->slug) }}">
                                    {{ $prod->name }}
                                </a>
                            </h3>

                            <!-- Specs Snippet -->
                            <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">
                                {{ $prod->specs }}
                            </p>
                        </div>

                        <!-- Price and Add to Cart Section -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Harga Resmi</span>
                                <div class="text-lg font-black text-rose-600 tracking-tight">
                                    Rp {{ number_format($prod->selling_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Add to Cart Form -->
                            @if($prod->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white text-xs font-bold shadow-md shadow-rose-200 transition-all transform hover:-translate-y-0.5">
                                        <i class="fa-solid fa-cart-plus"></i>
                                        <span>Beli</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="inline-flex items-center gap-1.5 px-3 py-2 rounded-2xl bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed">
                                    <i class="fa-solid fa-ban"></i>
                                    <span>Habis</span>
                                </button>
                            @endif
                        </div>

                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-rose-100 p-8">
                    <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Laptop tidak ditemukan</h3>
                    <p class="text-xs text-slate-500 mb-6 max-w-sm mx-auto">
                        Coba gunakan kata kunci pencarian lain atau pilih kategori yang tersedia.
                    </p>
                    <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-rose-500 text-white font-bold text-xs hover:bg-rose-600 transition-colors">
                        Lihat Semua Katalog Laptop
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>

    </section>

</div>
@endsection
