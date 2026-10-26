@extends('layouts.admin')

@section('title', 'Detail Laptop: ' . $product->name)
@section('page_title', 'Detail Produk Laptop')
@section('page_subtitle', 'Informasi spesifikasi lengkap dan riwayat transaksi inventaris')

@section('content')
<div class="space-y-6">

    <!-- Top Action Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Laptop
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product->id) }}" class="px-4 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Ubah Data
            </a>
            <a href="{{ route('store.show', $product->slug) }}" target="_blank" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs hover:bg-rose-100 transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka di Toko
            </a>
        </div>
    </div>

    <!-- Product Profile Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
        <div class="md:col-span-4 bg-slate-50 rounded-2xl p-6 flex items-center justify-center border border-slate-100">
            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=400&q=80' }}" 
                 alt="{{ $product->name }}" class="max-h-56 object-contain">
        </div>

        <div class="md:col-span-8 space-y-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                    {{ $product->category->name }}
                </span>
                <span class="font-mono font-bold text-slate-400">SKU: {{ $product->sku }}</span>
            </div>

            <h2 class="text-2xl font-black text-slate-900">{{ $product->name }}</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div>
                    <span class="text-slate-400">Harga Beli:</span>
                    <div class="font-bold text-slate-800 text-sm">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</div>
                </div>
                <div>
                    <span class="text-slate-400">Harga Jual:</span>
                    <div class="font-black text-rose-600 text-sm">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                </div>
                <div>
                    <span class="text-slate-400">Sisa Stok:</span>
                    <div class="font-bold {{ $product->stock <= $product->minimum_stock ? 'text-rose-600' : 'text-slate-900' }} text-sm">
                        {{ $product->stock }} unit
                    </div>
                </div>
                <div>
                    <span class="text-slate-400">Batas Min:</span>
                    <div class="font-semibold text-slate-700 text-sm">{{ $product->minimum_stock }} unit</div>
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-slate-400 font-bold uppercase text-[10px]">Spesifikasi:</span>
                <p class="font-mono text-slate-700 bg-white p-3 rounded-xl border border-slate-200/80 leading-relaxed">
                    {{ $product->specs ?: 'Belum ada spesifikasi.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Related Transactions (Purchases & Sales) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        
        <!-- Stock-In History -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-3">
            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-truck-ramp-box text-rose-500"></i> Riwayat Pembelian (Stock-In)
            </h3>
            <div class="divide-y divide-slate-100">
                @forelse($product->purchaseItems as $pItem)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-bold text-slate-800">{{ $pItem->purchase->purchase_number }}</div>
                            <div class="text-[10px] text-slate-400">{{ $pItem->purchase->supplier->name }} &bull; {{ $pItem->purchase->purchased_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-emerald-600">+{{ $pItem->quantity }} Unit</span>
                            <div class="text-[10px] text-slate-400">@ Rp {{ number_format($pItem->unit_cost, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-slate-400">Belum ada riwayat stock-in.</div>
                @endforelse
            </div>
        </div>

        <!-- Sales History -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-3">
            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-cash-register text-purple-500"></i> Riwayat Penjualan (Stock-Out)
            </h3>
            <div class="divide-y divide-slate-100">
                @forelse($product->saleItems as $sItem)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-bold text-slate-800">{{ $sItem->sale->sale_number }}</div>
                            <div class="text-[10px] text-slate-400">{{ $sItem->sale->customer_name ?: 'Pelanggan' }} &bull; {{ $sItem->sale->sold_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-rose-600">-{{ $sItem->quantity }} Unit</span>
                            <div class="text-[10px] text-slate-400">Rp {{ number_format($sItem->subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-slate-400">Belum ada riwayat penjualan.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
