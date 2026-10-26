@extends('layouts.admin')

@section('title', 'Pembelian (Stock-In) - ladydevice.store')
@section('page_title', 'Transaksi Pembelian (Stock-In)')
@section('page_subtitle', 'Penerimaan stok laptop dari pemasok distributor resmi dengan penambahan stok otomatis')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat Pembelian & Restock</h3>
                <p class="text-xs text-slate-400">Total terdata: {{ $purchases->total() }} nota transaksi Stock-In</p>
            </div>
            
            <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all self-start sm:self-auto">
                <i class="fa-solid fa-truck-ramp-box"></i> Buat Transaksi Stock-In Baru
            </a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('purchases.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
            <div class="sm:col-span-4 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. PO atau nama supplier..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>

            <div class="sm:col-span-3">
                <select name="supplier_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-400">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3 grid grid-cols-2 gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-xs text-slate-700">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-xs text-slate-700">
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'supplier_id', 'start_date', 'end_date']))
                    <a href="{{ route('purchases.index') }}" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:text-rose-600 transition-colors" title="Reset">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>

    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Nomor PO</th>
                        <th class="py-3 px-4">Pemasok / Supplier</th>
                        <th class="py-3 px-4">Tanggal Stock-In</th>
                        <th class="py-3 px-4">Item & Kuantitas</th>
                        <th class="py-3 px-4 text-right">Total Biaya PO</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                {{ $p->purchase_number }}
                                <div class="text-[10px] font-sans font-normal text-slate-400">Oleh: {{ $p->user->name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ $p->supplier->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $p->supplier->phone }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $p->purchased_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full font-bold bg-slate-100 text-slate-800 text-[11px]">
                                    {{ $p->items->sum('quantity') }} unit
                                </span>
                                <span class="text-[10px] text-slate-400">({{ $p->items->count() }} model)</span>
                            </td>
                            <td class="py-3 px-4 text-right font-black text-slate-900 font-mono">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('purchases.show', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                                    <i class="fa-solid fa-receipt text-rose-500"></i> Nota
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Belum ada transaksi Stock-In tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $purchases->links() }}
        </div>
    </div>

</div>
@endsection
