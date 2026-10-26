@extends('layouts.store')

@section('title', 'Keranjang Belanja - ladydevice.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <div>
        <h1 class="text-3xl font-black font-serif text-slate-900">Keranjang Belanja Anda</h1>
        <p class="text-xs text-slate-500 mt-1">Periksa kembali pilihan laptop estetik sebelum melanjutkan ke proses checkout</p>
    </div>

    @if(!empty($cart) && count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Cart Items Table -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-rose-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Barang ({{ count($cart) }} Item)</span>
                    <a href="{{ route('home') }}" class="text-xs text-rose-600 font-semibold hover:underline">
                        + Tambah Laptop Lain
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <div class="py-4 flex flex-col sm:flex-row items-center gap-4 justify-between">
                            
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=150&q=80' }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-16 h-16 object-contain rounded-2xl bg-slate-50 p-1.5 border border-slate-100 flex-shrink-0">
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 line-clamp-1">{{ $item['name'] }}</h4>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $item['sku'] }} &bull; {{ $item['color'] ?? '-' }}</div>
                                    <div class="text-xs font-bold text-rose-600 mt-1">
                                        Rp {{ number_format($item['selling_price'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                <!-- Quantity Modifier Buttons -->
                                <div class="flex items-center border border-rose-200 rounded-xl bg-slate-50 p-1">
                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-white text-slate-700 hover:bg-rose-100 font-bold text-xs flex items-center justify-center shadow-sm">
                                            -
                                        </button>
                                    </form>

                                    <span class="w-8 text-center text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>

                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-white text-slate-700 hover:bg-rose-100 font-bold text-xs flex items-center justify-center shadow-sm">
                                            +
                                        </button>
                                    </form>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right min-w-[100px]">
                                    <div class="text-xs font-black text-slate-900">
                                        Rp {{ number_format($item['selling_price'] * $item['quantity'], 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus dari keranjang">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Checkout Details Form -->
            <div class="lg:col-span-5 bg-white rounded-3xl border border-rose-100 p-6 sm:p-8 shadow-sm space-y-6">
                
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                    Informasi Pengiriman & Checkout
                </h3>

                <form action="{{ route('cart.checkout') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima *</label>
                        <input type="text" name="customer_name" required value="{{ auth()->user()->name ?? old('customer_name') }}" placeholder="Contoh: Clarissa Putri" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                        @error('customer_name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP Aktif *</label>
                        <input type="text" name="customer_phone" required value="{{ auth()->user()->phone ?? old('customer_phone') }}" placeholder="Contoh: 081288889999" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                        @error('customer_phone')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Pengiriman *</label>
                        <textarea name="shipping_address" rows="3" required placeholder="Jl. Sudirman No. 45, Apartemen Senopati Lantai 12..." 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Metode Pembayaran (QRIS, Transfer, Cash) -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs font-bold text-slate-700">Pilih Metode Pembayaran *</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <!-- Option 1: QRIS -->
                            <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all hover:border-rose-300 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/50 has-[:checked]:ring-2 has-[:checked]:ring-rose-200">
                                <input type="radio" name="payment_method" value="qris" checked class="sr-only">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-rose-600 uppercase bg-rose-100 px-1.5 py-0.2 rounded">Instan</span>
                                </div>
                                <span class="font-bold text-xs text-slate-800">QRIS</span>
                                <span class="text-[10px] text-slate-500 leading-tight mt-0.5">GoPay, OVO, Dana, Shopee, BCA</span>
                            </label>

                            <!-- Option 2: Transfer Bank -->
                            <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all hover:border-purple-300 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50/50 has-[:checked]:ring-2 has-[:checked]:ring-purple-200">
                                <input type="radio" name="payment_method" value="transfer" class="sr-only">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-building-columns"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-purple-600 uppercase bg-purple-100 px-1.5 py-0.2 rounded">Virtual</span>
                                </div>
                                <span class="font-bold text-xs text-slate-800">Transfer Bank</span>
                                <span class="text-[10px] text-slate-500 leading-tight mt-0.5">BCA, Mandiri, BNI, BRI</span>
                            </label>

                            <!-- Option 3: Cash -->
                            <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all hover:border-emerald-300 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50 has-[:checked]:ring-2 has-[:checked]:ring-emerald-200">
                                <input type="radio" name="payment_method" value="cash" class="sr-only">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-emerald-600 uppercase bg-emerald-100 px-1.5 py-0.2 rounded">Di Butik</span>
                                </div>
                                <span class="font-bold text-xs text-slate-800">Cash / Tunai</span>
                                <span class="text-[10px] text-slate-500 leading-tight mt-0.5">Bayar langsung di butik toko</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Minta packing kayu tambahan / bungkus kado..." 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                    </div>

                    <!-- Price Calculation Summary -->
                    <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal Barang:</span>
                            <span class="font-semibold text-slate-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Ongkos Kirim & Asuransi:</span>
                            <span class="font-bold text-emerald-600">GRATIS (Promo)</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-2 border-t border-slate-100 text-sm">
                            <span class="font-bold text-slate-900">Total Pembayaran:</span>
                            <span class="font-black text-rose-600 text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-lock"></i> Konfirmasi & Bayar Pesanan
                    </button>
                    
                    <p class="text-[11px] text-slate-400 text-center leading-tight">
                        Dengan menekan tombol di atas, transaksi Anda dilindungi dengan sistem inventaris atomik terintegrasi.
                    </p>

                </form>

            </div>

        </div>
    @else
        <!-- Empty Cart State -->
        <div class="bg-white rounded-3xl border border-rose-100 p-12 text-center max-w-lg mx-auto shadow-sm space-y-4">
            <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center mx-auto text-3xl">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <h3 class="text-xl font-bold font-serif text-slate-900">Keranjang Belanja Kosong</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Anda belum menambahkan laptop estetik ke dalam keranjang. Yuk jelajahi katalog laptop cantik kami!
            </p>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-rose-500 text-white font-bold text-xs shadow-md shadow-rose-200 hover:bg-rose-600 transition-all">
                    <i class="fa-solid fa-laptop"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
