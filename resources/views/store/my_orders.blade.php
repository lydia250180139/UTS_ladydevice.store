@extends('layouts.store')

@section('title', 'Pesanan Saya - ladydevice.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div>
        <h1 class="text-3xl font-black font-serif text-slate-900">Riwayat Pesanan Saya</h1>
        <p class="text-xs text-slate-500 mt-1">Lacak status transaksi pembelian laptop Anda di ladydevice.store</p>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-rose-100 p-6 shadow-sm space-y-4" x-data="{ cancelModalOpen: false }">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-sm text-slate-900">{{ $order->sale_number }}</span>
                            <span class="text-xs text-slate-400">&bull; {{ $order->sold_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            @if($order->status === 'completed')
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-check mr-1"></i> Pesanan Selesai
                                </span>
                            @elseif($order->status === 'pending_cancellation')
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                                    <i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu Konfirmasi Batal Owner
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-rose-100 text-rose-800 border border-rose-200">
                                    <i class="fa-solid fa-ban mr-1"></i> Pesanan Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product->image ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=100&q=80' }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-contain rounded-xl bg-slate-50 p-1 border border-slate-100">
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                        <div class="text-slate-400 font-mono">{{ $item->quantity }} unit &times; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="font-bold text-slate-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-500">Total Pembayaran:</span>
                            <span class="text-base font-black text-rose-600 ml-1">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('store.orderSuccess', $order->id) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                <i class="fa-solid fa-receipt mr-1"></i> Lihat Invoice
                            </a>

                            <!-- Cancellation Request Option (If completed and not yet cancelled) -->
                            @if($order->status === 'completed')
                                <button @click="cancelModalOpen = true" class="px-4 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors">
                                    <i class="fa-solid fa-rotate-left mr-1"></i> Ajukan Pembatalan
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Cancellation Modal -->
                    <div x-show="cancelModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                        <div @click.away="cancelModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl text-left">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-base text-slate-900">Permintaan Pembatalan Pesanan</h3>
                                <button @click="cancelModalOpen = false" class="text-slate-400 hover:text-slate-600">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <p class="text-xs text-slate-500">
                                Permohonan pembatalan pesanan <strong>#{{ $order->sale_number }}</strong> akan diteruskan ke <span class="text-rose-600 font-semibold">Dashboard Owner</span> untuk diverifikasi dan pengembalian stok barang.
                            </p>

                            <form action="{{ route('sales.requestCancellation', $order->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Pembatalan *</label>
                                    <textarea name="cancellation_reason" required rows="3" placeholder="Contoh: Salah memilih varian warna laptop..." 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2">
                                    <button type="button" @click="cancelModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-600 text-white hover:bg-rose-700 shadow-md shadow-rose-200">
                                        Kirimkan ke Owner
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach

            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-rose-100 p-12 text-center max-w-lg mx-auto shadow-sm space-y-4">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h3 class="text-lg font-bold font-serif text-slate-900">Belum Ada Riwayat Pesanan</h3>
            <p class="text-xs text-slate-500">
                Anda belum melakukan pembelian laptop di ladydevice.store.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-rose-500 text-white font-bold text-xs shadow-md shadow-rose-200 hover:bg-rose-600 transition-all">
                <i class="fa-solid fa-laptop"></i> Belanja Laptop Sekarang
            </a>
        </div>
    @endif

</div>
@endsection
