<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // 1. Core Summary Metrics
        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalSuppliers = Supplier::count();
        $totalUsers = User::count();

        // Sales Metrics
        $todaySales = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->sum('total');

        $monthSales = Sale::where('status', 'completed')
            ->where('sold_at', '>=', $thisMonth)
            ->sum('total');

        $totalCompletedSalesCount = Sale::where('status', 'completed')->count();
        $totalPurchasesCount = Purchase::where('status', 'completed')->count();

        // Pending Cancellations (Owner only special alert)
        $pendingCancellationsCount = Sale::where('status', 'pending_cancellation')->count();
        $pendingCancellations = Sale::with(['user', 'items.product'])
            ->where('status', 'pending_cancellation')
            ->latest('cancellation_requested_at')
            ->get();

        // 2. Automated Sales Diagram Data (Last 7 Days)
        $chartLabels = [];
        $chartRevenues = [];
        $chartOrderCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $label = $date->translatedFormat('d M');

            $dayRevenue = Sale::where('status', 'completed')
                ->whereDate('sold_at', $dateString)
                ->sum('total');

            $dayOrders = Sale::where('status', 'completed')
                ->whereDate('sold_at', $dateString)
                ->count();

            $chartLabels[] = $label;
            $chartRevenues[] = (float) $dayRevenue;
            $chartOrderCounts[] = (int) $dayOrders;
        }

        // 3. Category Breakdown Data (For Doughnut Chart)
        $categorySales = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.status', 'completed')
            ->select('categories.name as category_name', DB::raw('SUM(sale_items.subtotal) as total_revenue'), DB::raw('SUM(sale_items.quantity) as total_units'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        $catLabels = $categorySales->pluck('category_name')->toArray();
        $catRevenues = $categorySales->pluck('total_revenue')->map(fn($v) => (float)$v)->toArray();

        // 4. Top Selling Laptops
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->select('products.name', 'products.sku', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 5. Low Stock Products List
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->limit(6)
            ->get();

        // 6. Recent Sales
        $recentSales = Sale::with(['user', 'items'])
            ->latest('sold_at')
            ->limit(6)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'lowStockCount',
            'totalSuppliers',
            'totalUsers',
            'todaySales',
            'monthSales',
            'totalCompletedSalesCount',
            'totalPurchasesCount',
            'pendingCancellationsCount',
            'pendingCancellations',
            'chartLabels',
            'chartRevenues',
            'chartOrderCounts',
            'catLabels',
            'catRevenues',
            'topProducts',
            'lowStockProducts',
            'recentSales'
        ));
    }

    /**
     * Exclusive Owner Action: Confirm cancellation of a sale and atomically restore stock.
     */
    public function confirmCancellation(Request $request, Sale $sale)
    {
        // Enforce Owner authorization
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses Terlarang: Konfirmasi pembatalan pesanan hanya boleh dilakukan oleh akun Owner.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('info', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($sale, $request) {
            // Restore product stock with row lock
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            $sale->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->input('cancellation_reason') ?: ($sale->cancellation_reason ?: 'Dibatalkan dan disetujui langsung oleh Owner.'),
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
            ]);
        });

        return back()->with('success', "Pesanan #{$sale->sale_number} berhasil dibatalkan oleh Owner! Stok seluruh produk telah otomatis dikembalikan ke sistem inventaris.");
    }

    /**
     * Exclusive Owner Action: Reject cancellation request (keep sale completed)
     */
    public function rejectCancellation(Request $request, Sale $sale)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses Terlarang: Penolakan pembatalan pesanan hanya boleh dilakukan oleh akun Owner.');
        }

        $sale->update([
            'status' => 'completed',
            'cancellation_reason' => 'Permintaan pembatalan ditolak oleh Owner: ' . ($request->input('rejection_note') ?? 'Pesanan sudah dalam proses pengiriman.')
        ]);

        return back()->with('info', "Permintaan pembatalan #{$sale->sale_number} telah ditolak. Status pesanan tetap aktif/selesai.");
    }
}
