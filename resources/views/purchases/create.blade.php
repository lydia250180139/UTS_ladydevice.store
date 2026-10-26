@extends('layouts.admin')

@section('title', 'Buat Transaksi Stock-In - ladydevice.store')
@section('page_title', 'Input Transaksi Pembelian (Stock-In)')
@section('page_subtitle', 'Catat nota pembelian dari distributor dan tambah kuantitas stok produk secara otomatis')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="purchaseForm()">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('purchases.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat PO
        </a>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Header Info Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-invoice text-rose-500"></i> Informasi Pemasok & Nota Pembelian
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                
                <!-- Supplier Selection -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Supplier / Distributor *</label>
                    <select name="supplier_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('supplier_id') border-rose-500 @enderror">
                        <option value="">-- Pilih Distributor --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purchase Date -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Pembelian *</label>
                    <input type="date" name="purchased_at" value="{{ old('purchased_at', date('Y-m-d')) }}" required 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

                <!-- Operator / Staff -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Petugas Penerima (Staff/Owner)</label>
                    <input type="text" disabled value="{{ auth()->user()->name }} ({{ auth()->user()->role }})" 
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-500">
                </div>

                <!-- Notes -->
                <div class="sm:col-span-3">
                    <label class="block font-bold text-slate-700 mb-1">Catatan Transaksi / Nomor Surat Jalan Distributor</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: No. Faktur PT Global Tech: GT-99881..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                </div>

            </div>
        </div>

        <!-- Purchase Items Card (Dynamic multiple items) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-purple-500"></i> Daftar Laptop Yang Masuk (Stock-In)
                    </h3>
                    <p class="text-xs text-slate-400">Pilih laptop yang diterima dari distributor dan tentukan kuantitas penambah stok</p>
                </div>
                <button type="button" @click="addItem()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 text-xs font-bold transition-all">
                    <i class="fa-solid fa-plus"></i> Tambah Baris Laptop
                </button>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3 w-5/12">Model Laptop</th>
                            <th class="py-2.5 px-3 w-2/12 text-center">Kuantitas Masuk</th>
                            <th class="py-2.5 px-3 w-2/12 text-right">Harga Beli Unit (Rp)</th>
                            <th class="py-2.5 px-3 w-2/12 text-right">Subtotal (Rp)</th>
                            <th class="py-2.5 px-3 w-1/12 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="py-3 px-3">
                                    <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" @change="onProductSelect(index)" required 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                                        <option value="">-- Pilih Model Laptop --</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}" data-cost="{{ $prod->purchase_price }}">
                                                {{ $prod->name }} (Stok saat ini: {{ $prod->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" min="1" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" required 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-center font-bold text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" min="0" step="1000" :name="'items[' + index + '][unit_cost]'" x-model.number="item.unit_cost" required 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-right font-mono font-bold text-xs focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-black text-slate-800">
                                    Rp <span x-text="(item.quantity * item.unit_cost).toLocaleString('id-ID')"></span>
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
                <span class="font-bold text-slate-600 uppercase tracking-wider text-[11px]">Total Keseluruhan PO Stock-In:</span>
                <span class="text-xl font-black text-rose-600 font-mono">
                    Rp <span x-text="calculateTotal().toLocaleString('id-ID')"></span>
                </span>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold text-xs">
                Batal
            </a>
            <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all flex items-center gap-2">
                <i class="fa-solid fa-check"></i> Proses Stock-In & Tambah Stok
            </button>
        </div>

    </form>

</div>

<script>
    function purchaseForm() {
        return {
            items: [
                { product_id: '', quantity: 1, unit_cost: 0 }
            ],
            addItem() {
                this.items.push({ product_id: '', quantity: 1, unit_cost: 0 });
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
                    const cost = selectedOpt.getAttribute('data-cost');
                    if (cost) {
                        this.items[index].unit_cost = parseFloat(cost);
                    }
                }
            },
            calculateTotal() {
                return this.items.reduce((sum, item) => sum + ((item.quantity || 0) * (item.unit_cost || 0)), 0);
            }
        }
    }
</script>
@endsection
