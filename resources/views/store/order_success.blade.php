@extends('layouts.store')

@section('title', 'Pesanan Berhasil - ladydevice.store')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="bg-white rounded-3xl border border-rose-100 p-8 sm:p-12 shadow-xl text-center relative overflow-hidden">
        
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl mb-6 shadow-sm ring-8 ring-emerald-50 animate-bounce">
            <i class="fa-solid fa-check"></i>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 mb-2">
            Pesanan Berhasil Dikonfirmasi
        </span>

        <h1 class="text-2xl sm:text-3xl font-black font-serif text-slate-900 mb-2">
            Terima Kasih Atas Pesanan Anda!
        </h1>
        
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-8">
            Transaksi Anda telah berhasil dicatat. Stok laptop otomatis diperbarui di sistem inventaris ladydevice.store.
        </p>

        <!-- Invoice Details Card -->
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 text-left text-xs space-y-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-2">
                <div>
                    <span class="text-slate-400">Nomor Invoice Resmi:</span>
                    <div class="font-mono font-bold text-sm text-slate-900">{{ $sale->sale_number }}</div>
                </div>
                <div class="sm:text-right">
                    <span class="text-slate-400">Waktu Pembelian:</span>
                    <div class="font-semibold text-slate-700">{{ $sale->sold_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-200">
                <div>
                    <span class="text-slate-400">Penerima Paket:</span>
                    <div class="font-bold text-slate-800">{{ $sale->customer_name }}</div>
                    <div class="text-slate-600">{{ $sale->customer_phone }}</div>
                </div>
                <div>
                    <span class="text-slate-400">Alamat Pengiriman:</span>
                    <div class="text-slate-700 leading-relaxed">{{ $sale->shipping_address }}</div>
                </div>
            </div>

            <!-- Items Purchased -->
            <div class="space-y-2">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Rincian Laptop yang Dipesan:</span>
                @foreach($sale->items as $item)
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <span class="font-bold text-slate-800">{{ $item->product->name }}</span>
                            <span class="text-slate-500 font-mono">({{ $item->quantity }}x)</span>
                        </div>
                        <span class="font-bold text-slate-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Metode Pembayaran Terpilih -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-slate-500">Metode Pembayaran:</span>
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase 
                    {{ $sale->payment_method === 'qris' ? 'bg-rose-100 text-rose-800 border border-rose-300' : ($sale->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-purple-100 text-purple-800 border border-purple-300') }}">
                    <i class="fa-solid {{ $sale->payment_method === 'qris' ? 'fa-qrcode' : ($sale->payment_method === 'cash' ? 'fa-money-bill-wave' : 'fa-building-columns') }} mr-1"></i>
                    {{ $sale->payment_method_label }}
                </span>
            </div>

            <!-- Total Amount -->
            <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline text-sm">
                <span class="font-bold text-slate-800">Total Tagihan:</span>
                <span class="font-black text-rose-600 text-lg">
                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Instruksi Pembayaran Khusus Berdasarkan Metode (QRIS, Transfer, Cash) -->
        <div class="mb-8 p-6 rounded-2xl border text-left text-xs 
            {{ $sale->payment_method === 'qris' ? 'bg-rose-50/70 border-rose-200' : ($sale->payment_method === 'cash' ? 'bg-emerald-50/70 border-emerald-200' : 'bg-purple-50/70 border-purple-200') }}">
            
            @if($sale->payment_method === 'qris')
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Aesthetic QR Code Simulator -->
                    <div class="bg-white p-3 rounded-2xl border border-rose-200 shadow-sm flex flex-col items-center flex-shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode('LADYDEVICE-QRIS-'.$sale->sale_number.'-RP'.$sale->total) }}" 
                             alt="QRIS ladydevice.store" class="w-36 h-36 rounded-lg">
                        <span class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">NMID: ID1020261199</span>
                    </div>
                    <div class="space-y-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-200 text-rose-800 uppercase">
                            Pembayaran QRIS Instan
                        </span>
                        <h4 class="font-bold text-sm text-slate-900">Scan QRIS Menggunakan Aplikasi Favorit Anda</h4>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Buka BCA Mobile, GoPay, OVO, ShopeePay, Dana, atau Livin' Mandiri Anda, lalu arahkan kamera ke kode QRIS di samping. Nominal otomatis tertera sebesar <strong class="text-rose-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</strong>.
                        </p>
                        <div class="text-[10px] text-slate-500 flex items-center gap-2 pt-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Menunggu konfirmasi pembayaran otomatis (Verifikasi Real-time)</span>
                        </div>
                    </div>
                </div>

            @elseif($sale->payment_method === 'transfer')
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-200 text-purple-800 uppercase">
                            Transfer Bank Otomatis
                        </span>
                        <span class="text-slate-400 text-[10px]">Batas Waktu: 24 Jam</span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-900">Silakan Transfer ke Nomor Rekening Resmi ladydevice.store</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div class="bg-white p-3.5 rounded-xl border border-purple-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-800 text-xs">Bank Central Asia (BCA)</span>
                                <span class="text-[10px] font-bold text-blue-600">BCA</span>
                            </div>
                            <div class="font-mono font-bold text-sm text-purple-900 tracking-wider">8830-1928-1111</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">a.n. PT LadyDevice Teknologi</div>
                        </div>

                        <div class="bg-white p-3.5 rounded-xl border border-purple-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-800 text-xs">Bank Mandiri</span>
                                <span class="text-[10px] font-bold text-amber-600">MANDIRI</span>
                            </div>
                            <div class="font-mono font-bold text-sm text-purple-900 tracking-wider">137-00-9922-3344</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">a.n. PT LadyDevice Teknologi</div>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500">
                        Sertakan nomor invoice <strong class="text-slate-800">{{ $sale->sale_number }}</strong> pada berita transfer.
                    </p>
                </div>

            @else
                <div class="space-y-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-200 text-emerald-800 uppercase">
                        Pembayaran Tunai di Toko (Cash on Store)
                    </span>
                    <h4 class="font-bold text-sm text-slate-900">Pembayaran Saat Pengambilan Unit di Butik</h4>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        Laptop Anda telah diamankan dari stok etalase. Silakan lakukan pembayaran tunai atau debit langsung ke kasir kami dengan menunjukkan nomor transaksi: <strong class="text-slate-900">{{ $sale->sale_number }}</strong>.
                    </p>
                    <div class="bg-white p-3 rounded-xl border border-emerald-200 text-[11px] text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-rose-500 text-sm"></i>
                        <span><strong>Lokasi Butik:</strong> ladydevice.store Boutique, Grand Indonesia Mall Lantai 3, Jakarta Pusat (Buka 10.00 - 21.00 WIB)</span>
                    </div>
                </div>
            @endif

        </div>

        <!-- Navigation Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all">
                <i class="fa-solid fa-store mr-1.5"></i> Kembali Berbelanja
            </a>
            @auth
                <a href="{{ route('store.myOrders') }}" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition-all">
                    <i class="fa-solid fa-receipt mr-1.5 text-rose-500"></i> Lihat di Pesanan Saya
                </a>
            @endauth
        </div>

    </div>

</div>
@endsection
