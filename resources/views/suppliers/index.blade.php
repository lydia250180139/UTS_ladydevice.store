@extends('layouts.admin')

@section('title', 'Pemasok Laptop - ladydevice.store')
@section('page_title', 'Master Data Pemasok (Suppliers)')
@section('page_subtitle', 'Kelola distributor resmi, kontak perwakilan, dan riwayat pembelian')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Daftar Pemasok Distributor</h3>
            <p class="text-xs text-slate-400">Total terdaftar: {{ $suppliers->total() }} distributor resmi</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Tambah Supplier Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Nama Distributor</th>
                        <th class="py-3 px-4">Telepon / WhatsApp</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Alamat Kantor</th>
                        <th class="py-3 px-4 text-center">Stock-In Transaksi</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $sup)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <span>{{ $sup->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $sup->phone }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $sup->email ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 max-w-xs truncate">
                                {{ $sup->address ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $sup->purchases_count }} PO
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('suppliers.edit', $sup->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50" title="Ubah">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus distributor ini?')">
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
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada pemasok/supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $suppliers->links() }}
        </div>
    </div>

</div>
@endsection
