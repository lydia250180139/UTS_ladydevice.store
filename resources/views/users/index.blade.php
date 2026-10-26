@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Staff - ladydevice.store')
@section('page_title', 'Manajemen Pengguna & Role Akun')
@section('page_subtitle', 'Kelola akun Owner, Staff kasir toko, dan pelanggan terdaftar (Khusus Hak Akses Owner)')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                    <i class="fa-solid fa-crown mr-1"></i> Area Khusus Owner
                </span>
                <h3 class="text-base font-bold text-slate-900">Daftar Pengguna Sistem</h3>
            </div>
            <p class="text-xs text-slate-400 mt-1">Total akun terdaftar: {{ $users->total() }} akun</p>
        </div>

        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs shadow-md shadow-amber-200 hover:from-amber-600 hover:to-orange-600 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">No. HP</th>
                        <th class="py-3 px-4 text-center">Peran (Role)</th>
                        <th class="py-3 px-4 text-slate-400">Terdaftar Sejak</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-800 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-rose-400 to-pink-500 text-white font-bold flex items-center justify-center text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-[9px] text-amber-600 font-bold uppercase">(Akun Anda)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-mono">
                                {{ $user->email }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-mono">
                                {{ $user->phone ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase 
                                    {{ $user->role === 'owner' ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($user->role === 'staff' ? 'bg-purple-100 text-purple-800 border border-purple-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50" title="Ubah">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada akun pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
