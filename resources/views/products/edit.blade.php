@extends('layouts.admin')

@section('title', 'Ubah Produk Laptop - ladydevice.store')
@section('page_title', 'Ubah Produk Laptop')
@section('page_subtitle', 'Perbarui spesifikasi, harga beli/jual, dan batas stok laptop')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Ubah Data: {{ $product->name }}</h3>
                <p class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- SKU -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">SKU Produk (Kode Unik) *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('sku') border-rose-500 @enderror">
                    @error('sku')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori Laptop *</label>
                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('category_id') border-rose-500 @enderror">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Produk -->
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Nama Model Laptop *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Harga Beli -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Beli / Kulak (Rp) *</label>
                    <input type="number" step="1000" min="0" name="purchase_price" value="{{ old('purchase_price', (int)$product->purchase_price) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Harga Jual -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Jual Resmi (Rp) *</label>
                    <input type="number" step="1000" min="0" name="selling_price" value="{{ old('selling_price', (int)$product->selling_price) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Kuantitas Stok -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kuantitas Stok Tersedia (Unit) *</label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Batas Minimum Stok -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Batas Minimum Stok *</label>
                    <input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Warna -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Varian Warna Estetik</label>
                    <input type="text" name="color" value="{{ old('color', $product->color) }}" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- URL Gambar -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Produk</label>
                    <input type="url" name="image" value="{{ old('image', $product->image) }}" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Spesifikasi -->
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Rincian Spesifikasi Teknis</label>
                    <textarea name="specs" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">{{ old('specs', $product->specs) }}</textarea>
                </div>

                <!-- Deskripsi -->
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Produk</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Is Featured -->
                <div class="sm:col-span-2 flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} 
                        class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-400">
                    <label for="is_featured" class="font-bold text-slate-700">Tampilkan di Pilihan Unggulan (Featured)</label>
                </div>

            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
