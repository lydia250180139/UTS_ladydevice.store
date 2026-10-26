@extends('layouts.admin')

@section('title', 'Nota Pembelian ' . $purchase->purchase_number)
@section('page_title', 'Nota Transaksi Pembelian (Stock-In)')
@section('page_subtitle', 'Detail nota penerimaan stok barang dari distributor resmi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('purchases.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Pembelian
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-print"></i> Cetak Dokumen
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-8">
        
        <!-- Header PO -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-3 py-1 rounded-full">
                    Bukti Transaksi Stock-In
                </span>
                <h2 class="text-2xl font-black font-mono text-slate-900 mt-2">{{ $purchase->purchase_number }}</h2>
                <p class="text-xs text-slate-400">Tanggal Transaksi: {{ $purchase->purchased_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-800">
                    Status: {{ $purchase->status }}
                </span>
                <p class="text-xs text-slate-500 mt-2">Operator: <strong class="text-slate-800">{{ $purchase->user->name }}</strong></p>
            </div>
        </div>

        <!-- Supplier & Store Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-slate-50 text-xs">
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px]">Pemasok / Distributor:</span>
                <div class="font-bold text-sm text-slate-800 mt-0.5">{{ $purchase->supplier->name }}</div>
                <div class="text-slate-600">{{ $purchase->supplier->phone }} &bull; {{ $purchase->supplier->email ?: '-' }}</div>
                <div class="text-slate-500 mt-1">{{ $purchase->supplier->address ?: '-' }}</div>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px]">Tujuan Penerimaan Gudang:</span>
                <div class="font-bold text-sm text-slate-800 mt-0.5">ladydevice.store Boutique</div>
                <div class="text-slate-600">Catatan PO: {{ $purchase->notes ?: 'Tidak ada catatan tambahan.' }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-y border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Laptop & SKU</th>
                        <th class="py-3 px-4 text-center">Kuantitas Masuk</th>
                        <th class="py-3 px-4 text-right">Harga Beli Unit</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($purchase->items as $item)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $item->product->sku }} &bull; {{ $item->product->category->name }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-900">
                                +{{ $item->quantity }} unit
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-slate-600">
                                Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline text-sm">
            <span class="font-bold text-slate-700">Total Keseluruhan PO:</span>
            <span class="text-2xl font-black text-rose-600 font-mono">
                Rp {{ number_format($purchase->total, 0, ',', '.') }}
            </span>
        </div>

    </div>

</div>
@endsection
