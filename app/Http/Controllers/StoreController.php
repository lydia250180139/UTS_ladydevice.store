<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StoreController extends Controller
{
    /**
     * Aesthetic Digital Storefront Catalog
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->whereNull('deleted_at');

        // Search Keyword
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($queryBuilder) use ($q) {
                $queryBuilder->where('name', 'like', "%{$q}%")
                             ->orWhere('sku', 'like', "%{$q}%")
                             ->orWhere('specs', 'like', "%{$q}%")
                             ->orWhere('color', 'like', "%{$q}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', fn($c) => $c->where('slug', $catSlug));
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'stock':
                $query->orderBy('stock', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderByDesc('is_featured')->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->orderBy('name')->get();

        // Count cart items
        $cartCount = $this->getCartCount();

        return view('store.index', compact('products', 'categories', 'cartCount'));
    }

    /**
     * Product detail page
     */
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(3)
            ->get();
        $cartCount = $this->getCartCount();

        return view('store.show', compact('product', 'relatedProducts', 'cartCount'));
    }

    /**
     * Add to Cart with user requested strict error handling:
     * "pelanggan dapat memasukkan kedalam keranjang dan kembali ke halaman awal saat terjadi error atau stok barang kosong."
     */
    public function addToCart(Request $request)
    {
        // Enforce: Pelanggan must login first before adding to cart or purchasing
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk ke akun pelanggan Anda terlebih dahulu untuk dapat memasukkan laptop ke troli belanja atau melakukan pembelian!');
        }

        if (!Auth::user()->isPelanggan()) {
            return redirect()->route('home')->with('warning', 'Anda saat ini login sebagai akun pengelola (' . strtoupper(Auth::user()->role) . '). Untuk berbelanja dan memasukkan barang ke troli, silakan gunakan akun Pelanggan.');
        }

        try {
            $productId = $request->input('product_id');
            $quantity = max(1, (int) $request->input('quantity', 1));

            $product = Product::find($productId);

            // Error Condition 1: Product not found
            if (!$product) {
                return redirect()->route('home')->with('error', 'Produk laptop yang Anda pilih tidak ditemukan atau sudah tidak aktif.');
            }

            // Error Condition 2: Stock is completely empty
            if ($product->stock <= 0) {
                return redirect()->route('home')->with('error', "Mohon maaf, laptop cantik '{$product->name}' sedang kosong / habis terjual! Silakan nantikan restock berikutnya.");
            }

            // Error Condition 3: Requested quantity exceeds current available stock
            $cart = session()->get('cart', []);
            $currentInCart = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
            $newTotalQty = $currentInCart + $quantity;

            if ($newTotalQty > $product->stock) {
                return redirect()->route('home')->with('error', "Permintaan melebihi sisa stok! Stok laptop '{$product->name}' saat ini hanya tersisa {$product->stock} unit.");
            }

            // Add or update session cart
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] = $newTotalQty;
            } else {
                $cart[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'selling_price' => (float) $product->selling_price,
                    'quantity' => $quantity,
                    'image' => $product->image,
                    'color' => $product->color,
                    'specs' => $product->specs,
                ];
            }

            session()->put('cart', $cart);

            return back()->with('success', "'{$product->name}' ({$product->color}) berhasil ditambahkan ke keranjang belanja Anda!");

        } catch (\Throwable $e) {
            // Requirement: "kembali ke halaman awal saat terjadi error"
            return redirect()->route('home')->with('error', 'Terjadi kendala saat menambahkan barang ke keranjang. Halaman telah disegarkan.');
        }
    }

    /**
     * Shopping Cart View
     */
    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk ke akun pelanggan Anda terlebih dahulu untuk melihat troli belanja!');
        }

        if (!Auth::user()->isPelanggan()) {
            return redirect()->route('home')->with('warning', 'Troli belanja hanya dapat diakses oleh akun Pelanggan.');
        }

        $cart = session()->get('cart', []);
        $cartCount = $this->getCartCount();

        // Verify stock status of all cart items
        $adjusted = false;
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->stock <= 0) {
                unset($cart[$id]);
                $adjusted = true;
            } elseif ($item['quantity'] > $product->stock) {
                $cart[$id]['quantity'] = $product->stock;
                $adjusted = true;
            }
        }

        if ($adjusted) {
            session()->put('cart', $cart);
            return redirect()->route('home')->with('warning', 'Beberapa laptop di keranjang Anda mengalami penyesuaian karena stok toko yang menipis atau habis.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['selling_price'] * $item['quantity'];
        }

        return view('store.cart', compact('cart', 'subtotal', 'cartCount'));
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $action = $request->input('action'); // 'increase', 'decrease'

            $cart = session()->get('cart', []);

            if (!isset($cart[$productId])) {
                return redirect()->route('home')->with('error', 'Produk tidak ditemukan di keranjang.');
            }

            $product = Product::find($productId);
            if (!$product || $product->stock <= 0) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
                return redirect()->route('home')->with('error', "Laptop tersebut baru saja kehabisan stok.");
            }

            if ($action === 'increase') {
                if ($cart[$productId]['quantity'] + 1 > $product->stock) {
                    return redirect()->route('home')->with('error', "Tidak dapat menambah kuantitas! Stok laptop '{$product->name}' hanya tersisa {$product->stock} unit.");
                }
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            }

            session()->put('cart', $cart);
            return back()->with('success', 'Keranjang belanja berhasil diperbarui.');

        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan sistem saat memperbarui keranjang.');
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return back()->with('info', 'Produk telah dihapus dari keranjang.');
    }

    /**
     * Customer Checkout Process with atomic lock & rollback on stock shortage
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk ke akun pelanggan Anda terlebih dahulu untuk menyelesaikan pesanan!');
        }

        if (!Auth::user()->isPelanggan()) {
            return redirect()->route('home')->with('warning', 'Transaksi checkout hanya dapat diproses oleh akun Pelanggan.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('home')->with('info', 'Keranjang belanja Anda masih kosong. Silakan pilih laptop impian Anda!');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:qris,transfer,cash'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $sale = DB::transaction(function () use ($cart, $validated) {
                $saleNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'sale_number' => $saleNumber,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_method'] === 'cash' ? 'unpaid' : 'paid',
                    'notes' => $validated['notes'] ?? 'Order checkout via storefront ladydevice.store',
                    'total' => 0,
                    'status' => 'completed',
                    'sold_at' => now(),
                ]);

                $total = 0;

                foreach ($cart as $productId => $item) {
                    // Lock product record for update to prevent concurrency bugs
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product || $product->stock < $item['quantity']) {
                        // Stock unavailable -> abort and return to home page
                        throw new \Exception("Stok laptop '{$item['name']}' tidak mencukupi atau sudah kosong saat checkout!");
                    }

                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $item['quantity'];
                    $total += $subtotal;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    // Decrement stock
                    $product->decrement('stock', $item['quantity']);
                }

                $sale->update(['total' => $total]);

                return $sale;
            });

            // Clear session cart
            session()->forget('cart');

            return redirect()->route('store.orderSuccess', $sale->id)->with('success', "Pesanan #{$sale->sale_number} berhasil dibuat! Tim LadyDevice akan segera memproses laptop impian Anda.");

        } catch (\Exception $e) {
            // Return to home page on error / stock shortage as required
            return redirect()->route('home')->with('error', $e->getMessage() ?: 'Terjadi kesalahan dalam pemrosesan transaksi. Mohon ulangi kembali.');
        }
    }

    /**
     * Order success page
     */
    public function orderSuccess($saleId)
    {
        $sale = Sale::with(['items.product.category'])->findOrFail($saleId);
        $cartCount = $this->getCartCount();

        return view('store.order_success', compact('sale', 'cartCount'));
    }

    /**
     * Customer orders history ("Pesanan Saya")
     */
    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk ke akun Anda untuk melihat riwayat pesanan.');
        }

        $orders = Sale::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest('sold_at')
            ->paginate(10);

        $cartCount = $this->getCartCount();

        return view('store.my_orders', compact('orders', 'cartCount'));
    }

    private function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += (int) $item['quantity'];
        }
        return $count;
    }
}
