@extends('layouts.admin')

@section('title', 'Tambah Supplier - ladydevice.store')
@section('page_title', 'Tambah Supplier Distributor')
@section('page_subtitle', 'Daftarkan pemasok resmi baru untuk restock inventaris')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Formulir Pemasok Baru</h3>
                <p class="text-xs text-slate-400">Informasi kontak distributor laptop resmi</p>
            </div>
            <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Perusahaan / Distributor *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT Global Tech Nusantara" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('name') border-rose-500 @enderror">
                @error('name')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="021-55667788 atau 0812..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('phone') border-rose-500 @enderror">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="order@distributor.co.id" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Gudang / Kantor</label>
                <textarea name="address" rows="3" placeholder="Alamat lengkap distributor..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">{{ old('address') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700">
                    Simpan Supplier
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
