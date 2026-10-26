@extends('layouts.admin')

@section('title', 'Katalog Produk Laptop - ladydevice.store')
@section('page_title', 'Master Data Produk Laptop')
@section('page_subtitle', 'Kelola inventaris laptop, SKU unik, harga beli/jual, dan batas minimum stok')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Inventaris Laptop</h3>
                <p class="text-xs text-slate-400">Total terdaftar: {{ $products->total() }} model laptop</p>
            </div>
            
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all self-start sm:self-auto">
                <i class="fa-solid fa-plus"></i> Tambah Laptop Baru
            </a>
        </div>

        <!-- Filter and Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
            <div class="sm:col-span-5 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama laptop, SKU, atau varian warna..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>

            <div class="sm:col-span-3">
                <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-400">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="stock_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-400">
                    <option value="">Semua Status Stok</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Menipis (&le; Min)</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'stock_status']))
                    <a href="{{ route('products.index') }}" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:text-rose-600 transition-colors" title="Reset">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>

    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Laptop</th>
                        <th class="py-3 px-4">SKU & Kategori</th>
                        <th class="py-3 px-4 text-right">Harga Beli</th>
                        <th class="py-3 px-4 text-right">Harga Jual</th>
                        <th class="py-3 px-4 text-center">Stok / Min</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $prod)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=100&q=80' }}" 
                                         alt="{{ $prod->name }}" class="w-10 h-10 object-contain rounded-xl bg-slate-50 p-1 border border-slate-100 flex-shrink-0">
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $prod->name }}</div>
                                        <div class="text-[10px] text-slate-400">Warna: {{ $prod->color ?: '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-mono font-bold text-slate-700">{{ $prod->sku }}</div>
                                <span class="inline-block mt-0.5 text-[10px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.2 rounded-md">
                                    {{ $prod->category->name }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500 font-mono">
                                Rp {{ number_format($prod->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-black text-rose-600 font-mono">
                                Rp {{ number_format($prod->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold {{ $prod->stock <= $prod->minimum_stock ? 'text-rose-600' : 'text-slate-800' }}">
                                    {{ $prod->stock }}
                                </span>
                                <span class="text-slate-400 text-[10px]">/ {{ $prod->minimum_stock }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($prod->stock > $prod->minimum_stock)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        Tersedia
                                    </span>
                                @elseif($prod->stock > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800 animate-pulse">
                                        Menipis
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-200 text-slate-700">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('products.show', $prod->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $prod->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50" title="Ubah">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mengarsipkan produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Tidak ada produk laptop yang sesuai kriteria pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
