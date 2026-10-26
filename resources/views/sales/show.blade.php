@extends('layouts.admin')

@section('title', 'Detail Penjualan ' . $sale->sale_number)
@section('page_title', 'Detail Nota Penjualan')
@section('page_subtitle', 'Struk resmi transaksi penjualan laptop ladydevice.store')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('sales.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Penjualan
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
    </div>

    <!-- Cancellation Status Banner -->
    @if($sale->status === 'cancelled')
        <div class="p-5 rounded-3xl bg-rose-50 border border-rose-300 text-rose-900 text-xs shadow-sm flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-rose-200 text-rose-700 flex items-center justify-center font-bold flex-shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div class="space-y-1">
                <strong class="font-bold text-rose-800 text-sm">Pesanan Dibatalkan Resmi:</strong>
                <p>Transaksi ini telah dibatalkan dan stok seluruh laptop telah otomatis dikembalikan ke sistem inventaris.</p>
                <div class="text-[11px] text-rose-700 pt-1">
                    Dikonfirmasi oleh: <strong>{{ $sale->cancelledByUser->name ?? 'Owner' }}</strong> pada {{ $sale->cancelled_at ? $sale->cancelled_at->format('d/m/Y H:i') : '-' }} &bull; Alasan: <em>{{ $sale->cancellation_reason ?: '-' }}</em>
                </div>
            </div>
        </div>
    @elseif($sale->status === 'pending_cancellation')
        <div class="p-5 rounded-3xl bg-amber-50 border border-amber-300 text-amber-900 text-xs shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-200 text-amber-700 flex items-center justify-center font-bold flex-shrink-0 animate-pulse">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <strong class="font-bold text-amber-900 text-sm">Menunggu Konfirmasi Pembatalan:</strong>
                    <p class="text-amber-800 mt-0.5">Alasan: <em>{{ $sale->cancellation_reason ?: 'Permintaan pembatalan diajukan.' }}</em></p>
                </div>
            </div>

            <!-- Owner Confirmation Button directly on invoice -->
            @if(auth()->user()->isOwner())
                <form action="{{ route('owner.sales.confirmCancellation', $sale->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembatalan pesanan dan kembalikan stok barang ke inventaris sekarang?')">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 text-white font-bold text-xs hover:from-rose-700 hover:to-pink-700 shadow-md shadow-rose-200 transition-all flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-crown text-amber-300"></i> Konfirmasi Pembatalan (Owner)
                    </button>
                </form>
            @else
                <span class="text-[11px] text-slate-500 italic bg-white/70 px-3 py-1.5 rounded-xl border border-amber-200">
                    <i class="fa-solid fa-lock mr-1"></i> Menunggu Otorisasi Owner
                </span>
            @endif
        </div>
    @endif

    <!-- Invoice Sheet -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-3 py-1 rounded-full">
                    Struk Penjualan Resmi
                </span>
                <h2 class="text-2xl font-black font-mono text-slate-900 mt-2">{{ $sale->sale_number }}</h2>
                <p class="text-xs text-slate-400">Waktu Transaksi: {{ $sale->sold_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                    {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($sale->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                    Status: {{ $sale->status }}
                </span>
                <p class="text-xs text-slate-500 mt-2">Kasir/Akun: <strong class="text-slate-800">{{ $sale->user->name }}</strong></p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-slate-50 text-xs">
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px]">Data Pelanggan:</span>
                <div class="font-bold text-sm text-slate-800 mt-0.5">{{ $sale->customer_name ?: 'Pelanggan Walk-In' }}</div>
                <div class="text-slate-600">{{ $sale->customer_phone ?: '-' }}</div>
                <div class="text-slate-500 mt-1">{{ $sale->shipping_address ?: 'Ambil langsung di toko ladydevice.store' }}</div>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px]">Metode Pembayaran:</span>
                <div class="mt-1">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase 
                        {{ $sale->payment_method === 'qris' ? 'bg-rose-100 text-rose-800' : ($sale->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800') }}">
                        <i class="fa-solid {{ $sale->payment_method === 'qris' ? 'fa-qrcode' : ($sale->payment_method === 'cash' ? 'fa-money-bill-wave' : 'fa-building-columns') }} mr-1"></i>
                        {{ $sale->payment_method_label }}
                    </span>
                </div>
                <div class="text-slate-600 mt-2 text-[11px]">Catatan Pesanan: {{ $sale->notes ?: '-' }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-y border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Laptop & SKU</th>
                        <th class="py-3 px-4 text-center">Kuantitas</th>
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sale->items as $item)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $item->product->sku }} &bull; {{ $item->product->category->name }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-900">
                                {{ $item->quantity }} unit
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-slate-600">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
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
            <span class="font-bold text-slate-700">Total Transaksi:</span>
            <span class="text-2xl font-black text-rose-600 font-mono">
                Rp {{ number_format($sale->total, 0, ',', '.') }}
            </span>
        </div>

    </div>

</div>
@endsection
