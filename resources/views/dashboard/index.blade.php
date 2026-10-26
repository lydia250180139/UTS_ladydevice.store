@extends('layouts.admin')

@section('title', 'Dashboard Terpadu - ladydevice.store')
@section('page_title', 'Dashboard Terpadu Bisnis')
@section('page_subtitle', 'Pantau performa penjualan, grafik otomatis, status stok, dan otorisasi transaksi')

@section('content')
<div class="space-y-8">

    <!-- 1. Top Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Penjualan Hari Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Penjualan Hari Ini</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight">
                Rp {{ number_format($todaySales, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                <span class="text-emerald-600 font-bold"><i class="fa-solid fa-arrow-trend-up"></i> Real-time</span>
                <span>omset penjualan hari ini</span>
            </p>
        </div>

        <!-- Card 2: Total Penjualan Bulan Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Omset Bulan Ini</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight">
                Rp {{ number_format($monthSales, 0, ',', '.') }}
            </div>
            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                <span class="text-purple-600 font-bold">{{ $totalCompletedSalesCount }}</span>
                <span>total transaksi selesai</span>
            </p>
        </div>

        <!-- Card 3: Total Produk & Stok Rendah -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Katalog Laptop</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-laptop"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight flex items-baseline gap-2">
                <span>{{ $totalProducts }}</span>
                <span class="text-xs font-semibold text-slate-400">Model Laptop</span>
            </div>
            <p class="text-xs mt-2 flex items-center gap-1.5">
                @if($lowStockCount > 0)
                    <span class="text-rose-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $lowStockCount }} model
                    </span>
                    <span class="text-rose-600">stok menipis / kritis</span>
                @else
                    <span class="text-emerald-600 font-bold"><i class="fa-solid fa-check"></i> Semua</span>
                    <span class="text-slate-500">stok berada dalam batas aman</span>
                @endif
            </p>
        </div>

        <!-- Card 4: Status Pembatalan & Otorisasi -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Otorisasi Pembatalan</span>
                <div class="w-10 h-10 rounded-2xl {{ $pendingCancellationsCount > 0 ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center font-bold">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <div class="text-2xl font-black {{ $pendingCancellationsCount > 0 ? 'text-amber-700' : 'text-slate-900' }} tracking-tight">
                {{ $pendingCancellationsCount }} <span class="text-xs font-semibold text-slate-400">Permintaan</span>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                @if(auth()->user()->isOwner())
                    <a href="#cancellation-section" class="text-amber-600 font-bold hover:underline flex items-center gap-1">
                        Tinjau di panel Owner <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @else
                    <span class="text-slate-400">Hanya dapat dikonfirmasi Owner</span>
                @endif
            </p>
        </div>

    </div>

    <!-- 2. DIAGRAM OTOMATIS PENJUALAN (Charts & Graphs) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Line/Bar Chart: Tren Penjualan 7 Hari Terakhir -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-rose-500"></i> Diagram Otomatis Penjualan (7 Hari)
                    </h3>
                    <p class="text-xs text-slate-400">Grafik tren pendapatan penjualan harian ladydevice.store terhitung otomatis dari database</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> Dynamic SQL Agregate
                    </span>
                </div>
            </div>
            
            <div class="h-72 w-full">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Distribusi Penjualan Berdasarkan Kategori -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-chart-pie text-purple-500"></i> Porsi Kategori Laptop
                </h3>
                <p class="text-xs text-slate-400 mb-4">Kontribusi omset penjualan per kategori laptop</p>
                
                <div class="h-56 relative flex items-center justify-center">
                    <canvas id="categoryShareChart"></canvas>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 text-center">
                Visualisasi otomatis berbasis transaksi yang berstatus <strong class="text-emerald-600">Completed</strong>
            </div>
        </div>

    </div>

    <!-- 3. KHUSUS OWNER: KONFIRMASI PEMBATALAN PESANAN (Owner Exclusive Action) -->
    <div id="cancellation-section" class="bg-white rounded-3xl border-2 {{ auth()->user()->isOwner() ? 'border-amber-300' : 'border-slate-200' }} p-6 shadow-sm relative overflow-hidden">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">
                        <i class="fa-solid fa-crown mr-1"></i> Hak Akses Eksklusif Owner
                    </span>
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Konfirmasi Pembatalan Pesanan & Restorasi Stok
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Hanya akun <strong class="text-amber-700">Owner</strong> yang memiliki hak memvalidasi pembatalan transaksi dan mengembalikan kuantitas stok laptop ke database secara otomatis.
                </p>
            </div>

            @if(!auth()->user()->isOwner())
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-500 text-xs font-semibold">
                    <i class="fa-solid fa-lock text-slate-400"></i> Terkunci untuk Akun Staff
                </div>
            @endif
        </div>

        <!-- Cancellation Items List -->
        <div class="mt-6">
            @if($pendingCancellations->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingCancellations as $sale)
                        <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm text-slate-900">{{ $sale->sale_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                                        Menunggu Konfirmasi Owner
                                    </span>
                                </div>
                                <div class="text-xs text-slate-700">
                                    <strong>Pelanggan:</strong> {{ $sale->customer_name }} ({{ $sale->customer_phone ?? '-' }}) &bull;
                                    <strong>Total:</strong> <span class="font-bold text-rose-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-xs text-slate-600 bg-white/70 p-2.5 rounded-xl border border-amber-200 mt-2">
                                    <span class="font-bold text-amber-900"><i class="fa-solid fa-circle-question"></i> Alasan Pembatalan:</span>
                                    <span class="italic text-slate-700">{{ $sale->cancellation_reason ?: 'Permintaan pembatalan dari pemesan.' }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1">
                                    <strong>Item Laptop yang Akan Dikembalikan ke Stok:</strong>
                                    @foreach($sale->items as $item)
                                        <span class="inline-block bg-white px-2 py-0.5 rounded-md border border-slate-200 text-slate-700 font-semibold mr-1">
                                            {{ $item->product->name }} (+{{ $item->quantity }} unit)
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Actions for Owner -->
                            @if(auth()->user()->isOwner())
                                <div class="flex flex-col sm:flex-row items-center gap-2">
                                    <form action="{{ route('owner.sales.confirmCancellation', $sale->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi #{{ $sale->sale_number }} dan mengembalikan seluruh stok produk ke sistem?')">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 text-white font-bold text-xs hover:from-rose-700 hover:to-pink-700 shadow-md shadow-rose-200 transition-all">
                                            <i class="fa-solid fa-rotate-left"></i> Konfirmasi Pembatalan & Restorasi Stok
                                        </button>
                                    </form>

                                    <form action="{{ route('owner.sales.rejectCancellation', $sale->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan pembatalan ini dan pertahankan status pesanan?')">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-xs transition-colors">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-xs text-slate-400 italic">
                                    Tindakan hanya tersedia saat login sebagai Owner.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400 text-xs">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-check text-lg"></i>
                    </div>
                    Tidak ada antrean pembatalan pesanan saat ini. Seluruh pesanan berjalan lancar!
                </div>
            @endif
        </div>

    </div>

    <!-- 4. Low Stock Alerts & Top Selling Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Low Stock Alerts Table (PPT Requirement) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Peringatan Stok Menipis / Kritis
                    </h3>
                    <p class="text-xs text-slate-400">Laptop dengan kuantitas &le; batas minimum stok toko</p>
                </div>
                <a href="{{ route('purchases.create') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl border border-rose-200 transition-colors">
                    <i class="fa-solid fa-plus"></i> Tambah Stock-In
                </a>
            </div>

            @if($lowStockProducts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-y border-slate-100">
                            <tr>
                                <th class="py-2.5 px-3">Produk Laptop</th>
                                <th class="py-2.5 px-3 text-center">Sisa Stok</th>
                                <th class="py-2.5 px-3 text-center">Batas Min</th>
                                <th class="py-2.5 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lowStockProducts as $prod)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-3">
                                        <div class="font-bold text-slate-800">{{ $prod->name }}</div>
                                        <div class="text-[10px] font-mono text-slate-400">{{ $prod->sku }} &bull; {{ $prod->category->name }}</div>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full font-bold {{ $prod->stock <= 0 ? 'bg-red-100 text-red-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $prod->stock }} unit
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center text-slate-500 font-semibold">
                                        {{ $prod->minimum_stock }} unit
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <a href="{{ route('purchases.create') }}" class="text-rose-600 hover:text-rose-800 font-bold hover:underline">
                                            Restock PO &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-slate-400 text-xs">
                    Semua stok laptop berada dalam status aman dan di atas batas minimum.
                </div>
            @endif
        </div>

        <!-- Top Selling Laptops Ranking -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-fire text-amber-500"></i> Laptop Terlaris (Best Seller)
                    </h3>
                    <p class="text-xs text-slate-400">Model laptop dengan volume penjualan tertinggi</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-slate-500 hover:text-rose-600">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($topProducts as $index => $top)
                    <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center {{ $index === 0 ? 'bg-amber-100 text-amber-800 font-extrabold' : ($index === 1 ? 'bg-slate-200 text-slate-700' : 'bg-rose-50 text-rose-700') }}">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">{{ $top->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $top->sku }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-black text-rose-600">{{ $top->total_qty }} Unit Terjual</div>
                            <div class="text-[10px] text-slate-400">Rp {{ number_format($top->total_revenue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada data penjualan tercatat.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 5. Recent Sales Transactions Table -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-600"></i> Transaksi Penjualan Terbaru
                </h3>
                <p class="text-xs text-slate-400">Riwayat transaksi penjualan toko ladydevice.store</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('sales.create') }}" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold text-xs shadow-md shadow-rose-200 hover:from-rose-600 hover:to-pink-700 transition-all">
                    <i class="fa-solid fa-plus"></i> Transaksi Baru (POS)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-y border-slate-100">
                    <tr>
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Total Transaksi</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSales as $sale)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                {{ $sale->sale_number }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-800">{{ $sale->customer_name ?: 'Pelanggan Walk-In' }}</div>
                                <div class="text-[10px] text-slate-400">Kasir/Akun: {{ $sale->user->name }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $sale->sold_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($sale->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        Completed
                                    </span>
                                @elseif($sale->status === 'pending_cancellation')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                        Pending Batal
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-black text-slate-900">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('sales.show', $sale->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada transaksi penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sales Trend Line Chart (Last 7 Days)
        const ctxTrend = document.getElementById('salesTrendChart').getContext('2d');
        
        // Gradient background for chart
        const gradient = ctxTrend.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(244, 63, 94, 0.35)');
        gradient.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: @json($chartRevenues),
                    borderColor: '#f43f5e',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#e11d48',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.35,
                    fill: true,
                    backgroundColor: gradient,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: '"Plus Jakarta Sans"' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: {
                            font: { size: 10, family: '"Plus Jakarta Sans"' },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // 2. Category Share Doughnut Chart
        const ctxCat = document.getElementById('categoryShareChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: @json($catLabels),
                datasets: [{
                    data: @json($catRevenues),
                    backgroundColor: [
                        '#f43f5e', // rose
                        '#8b5cf6', // purple
                        '#ec4899', // pink
                        '#06b6d4', // cyan
                        '#f59e0b', // amber
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 10, family: '"Plus Jakarta Sans"' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed;
                                return ' ' + context.label + ': Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
