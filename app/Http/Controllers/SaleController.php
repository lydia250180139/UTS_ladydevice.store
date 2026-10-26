<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items.product', 'cancelledByUser'])->latest('sold_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('sold_at', [
                Carbon::parse($request->input('start_date'))->startOfDay(),
                Carbon::parse($request->input('end_date'))->endOfDay(),
            ]);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['nullable', 'in:qris,transfer,cash'],
            'notes' => ['nullable', 'string', 'max:500'],
            'sold_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $saleNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'sale_number' => $saleNumber,
                    'customer_name' => $request->input('customer_name'),
                    'customer_phone' => $request->input('customer_phone'),
                    'shipping_address' => $request->input('shipping_address'),
                    'payment_method' => $request->input('payment_method', 'cash'),
                    'payment_status' => 'paid',
                    'notes' => $request->input('notes'),
                    'total' => 0,
                    'status' => 'completed',
                    'sold_at' => $request->filled('sold_at') ? Carbon::parse($request->input('sold_at')) : now(),
                ]);

                $total = 0;

                foreach ($request->input('items') as $itemData) {
                    $qty = (int) $itemData['quantity'];
                    if ($qty < 1) {
                        throw ValidationException::withMessages([
                            'items' => 'Kuantitas produk yang dibeli minimal 1 unit.'
                        ]);
                    }

                    // Lock row to prevent race conditions and overselling
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                    // Strict stock validation
                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Stok laptop '{$product->name}' tidak mencukupi! Sisa stok tersedia: {$product->stock} unit, sedangkan permintaan: {$qty} unit."
                        ]);
                    }

                    // Calculate subtotal from authoritative server-side selling price
                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $total += $subtotal;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    // Decrement stock only on success
                    $product->decrement('stock', $qty);
                }

                $sale->update(['total' => $total]);

                return $sale;
            });

            return redirect()->route('sales.show', $sale)->with('success', "Transaksi Kasir POS #{$sale->sale_number} berhasil diproses dan stok telah berkurang!");

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'cancelledByUser', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Request cancellation (can be initiated by customer or cashier staff)
     */
    public function requestCancellation(Request $request, Sale $sale)
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        if ($sale->status === 'cancelled') {
            return back()->with('info', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        $sale->update([
            'status' => 'pending_cancellation',
            'cancellation_reason' => $request->input('cancellation_reason'),
            'cancellation_requested_at' => now(),
        ]);

        return back()->with('success', 'Permintaan pembatalan pesanan telah dikirimkan ke Dashboard Owner untuk ditinjau dan dikonfirmasi.');
    }
}
