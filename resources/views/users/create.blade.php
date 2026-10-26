@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru - ladydevice.store')
@section('page_title', 'Tambah Pengguna / Staff Baru')
@section('page_subtitle', 'Buat akun dengan hak akses Owner, Staff Kasir, atau Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Formulir Pengguna Baru</h3>
                <p class="text-xs text-slate-400">Pilih role hak akses dengan cermat</p>
            </div>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rania Zahira" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('name') border-rose-500 @enderror">
                @error('name')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@ladydevice.store" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('email') border-rose-500 @enderror">
                @error('email')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Hak Akses / Role Akun *</label>
                <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Kasir / Admin Toko</option>
                    <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner (Pemilik Toko - Akses Penuh)</option>
                    <option value="pelanggan" {{ old('role') === 'pelanggan' ? 'selected' : '' }}>Pelanggan (Customer)</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kata Sandi Akun *</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('password') border-rose-500 @enderror">
                @error('password')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold shadow-md shadow-amber-200 hover:from-amber-600 hover:to-orange-600">
                    Simpan Pengguna
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
