@extends('layouts.admin')

@section('title', 'Transaksi Kasir POS - ladydevice.store')
@section('page_title', 'Kasir Penjualan Laptop (Point of Sale)')
@section('page_subtitle', 'Proses transaksi penjualan langsung di toko dengan validasi stok ketat dan proteksi overselling')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="posForm()">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('sales.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Penjualan
        </a>
    </div>

    <!-- Error state banner if stock insufficient -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs shadow-md space-y-1">
            <div class="font-bold flex items-center gap-2 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation"></i> Transaksi Gagal Diproses:
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Customer & Header Info -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-user-tag text-rose-500"></i> Informasi Pelanggan & Kasir
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Pelanggan *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Contoh: dr. Amanda Saraswati" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="081233445566" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kasir / Operator</label>
                    <input type="text" disabled value="{{ auth()->user()->name }} ({{ auth()->user()->role }})" 
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Alamat Pengiriman (Jika dikirim ke rumah)</label>
                    <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Ambil di toko ladydevice.store atau masukkan alamat pengiriman..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran Kasir *</label>
                    <select name="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white font-semibold">
                        <option value="cash" selected>Cash / Tunai Kasir</option>
                        <option value="qris">QRIS Instant (Static / Dynamic)</option>
                        <option value="transfer">Transfer Bank (BCA / Mandiri)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Bonus sleeve case / mouse wireless..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

            </div>
        </div>

        <!-- Sales Items List (Dynamic) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-laptop text-rose-500"></i> Pilih Laptop Yang Dibeli
                    </h3>
                    <p class="text-xs text-slate-400">Pilih laptop yang tersedia dalam stok dan tentukan kuantitas pembelian</p>
                </div>
                <button type="button" @click="addItem()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition-all">
                    <i class="fa-solid fa-plus"></i> Tambah Laptop
                </button>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3 w-5/12">Laptop Terpilih</th>
                            <th class="py-2.5 px-3 w-2/12 text-center">Stok Tersedia</th>
                            <th class="py-2.5 px-3 w-2/12 text-center">Qty Beli</th>
                            <th class="py-2.5 px-3 w-2/12 text-right">Harga Jual Unit</th>
                            <th class="py-2.5 px-3 w-2/12 text-right">Subtotal</th>
                            <th class="py-2.5 px-3 w-1/12 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="py-3 px-3">
                                    <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" @change="onProductSelect(index)" required 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                                        <option value="">-- Pilih Seri Laptop --</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-stock="{{ $prod->stock }}">
                                                {{ $prod->name }} (Sisa Stok: {{ $prod->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[11px]" 
                                          :class="item.stock <= 3 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800'">
                                        <span x-text="item.stock || 0"></span> unit
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" min="1" :max="item.stock || 99" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" required 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-center font-bold text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-slate-600">
                                    Rp <span x-text="(item.unit_price || 0).toLocaleString('id-ID')"></span>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-black text-rose-600">
                                    Rp <span x-text="((item.quantity || 0) * (item.unit_price || 0)).toLocaleString('id-ID')"></span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <button type="button" @click="removeItem(index)" :disabled="items.length <= 1" 
                                        class="p-2 text-slate-400 hover:text-rose-600 disabled:opacity-30 disabled:cursor-not-allowed">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Total Bar -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-bold text-slate-600 uppercase tracking-wider text-[11px]">Total Pembayaran Kasir POS:</span>
                <span class="text-2xl font-black text-rose-600 font-mono">
                    Rp <span x-text="calculateTotal().toLocaleString('id-ID')"></span>
                </span>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('sales.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold text-xs">
                Batal
            </a>
            <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all flex items-center gap-2">
                <i class="fa-solid fa-receipt"></i> Selesaikan Transaksi Penjualan
            </button>
        </div>

    </form>

</div>

<script>
    function posForm() {
        return {
            items: [
                { product_id: '', quantity: 1, unit_price: 0, stock: 0 }
            ],
            addItem() {
                this.items.push({ product_id: '', quantity: 1, unit_price: 0, stock: 0 });
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            onProductSelect(index) {
                const selectEl = document.querySelectorAll("select[name*='product_id']")[index];
                if (selectEl) {
                    const selectedOpt = selectEl.options[selectEl.selectedIndex];
                    const price = selectedOpt.getAttribute('data-price');
                    const stock = selectedOpt.getAttribute('data-stock');
                    if (price) {
                        this.items[index].unit_price = parseFloat(price);
                        this.items[index].stock = parseInt(stock) || 0;
                    }
                }
            },
            calculateTotal() {
                return this.items.reduce((sum, item) => sum + ((item.quantity || 0) * (item.unit_price || 0)), 0);
            }
        }
    }
</script>
@endsection
