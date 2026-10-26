@extends('layouts.admin')

@section('title', 'Ubah Kategori - ladydevice.store')
@section('page_title', 'Ubah Kategori Laptop')
@section('page_subtitle', 'Perbarui nama atau ikon kategori produk')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Ubah Kategori: {{ $category->name }}</h3>
                <p class="text-xs text-slate-400 font-mono">{{ $category->slug }}</p>
            </div>
            <a href="{{ route('categories.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('name') border-rose-500 @enderror">
                @error('name')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Kategori</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
