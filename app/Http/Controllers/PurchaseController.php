<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(?Request $request = null)
    {
        $request = $request ?? request();
        $query = Purchase::with(['supplier', 'user', 'items.product'])->latest('purchased_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchased_at', [
                Carbon::parse($request->input('start_date'))->startOfDay(),
                Carbon::parse($request->input('end_date'))->endOfDay(),
            ]);
        }

        $purchases = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase = DB::transaction(function () use ($request) {
            $purchaseNumber = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $purchase = Purchase::create([
                'supplier_id' => $request->input('supplier_id'),
                'user_id' => auth()->id(),
                'purchase_number' => $purchaseNumber,
                'total' => 0,
                'status' => 'completed',
                'notes' => $request->input('notes'),
                'purchased_at' => Carbon::parse($request->input('purchased_at')),
            ]);

            $total = 0;

            foreach ($request->input('items') as $itemData) {
                $qty = (int) $itemData['quantity'];
                $cost = (float) $itemData['unit_cost'];

                if ($qty < 1 || $cost < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Kuantitas dan harga beli setiap produk harus valid (kuantitas > 0).'
                    ]);
                }

                // Row-lock product to safely increment stock
                $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                $subtotal = $qty * $cost;
                $total += $subtotal;

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $cost,
                    'subtotal' => $subtotal,
                ]);

                // Increment stock atomically
                $product->increment('stock', $qty);

                // Optionally update purchase price if supplier cost changed
                $product->update(['purchase_price' => $cost]);
            }

            $purchase->update(['total' => $total]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)->with('success', "Transaksi Stock-In #{$purchase->purchase_number} berhasil disimpan dan stok otomatis bertambah!");
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.category']);
        return view('purchases.show', compact('purchase'));
    }
}
