<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - ladydevice.store
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC STOREFRONT (LADYDEVICE.STORE)
// ==========================================
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/product/{slug}', [StoreController::class, 'show'])->name('store.show');

// Shopping Cart Actions
Route::post('/cart/add', [StoreController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [StoreController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/update', [StoreController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove/{productId}', [StoreController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/checkout', [StoreController::class, 'checkout'])->name('cart.checkout');
Route::get('/order-success/{id}', [StoreController::class, 'orderSuccess'])->name('store.orderSuccess');

// Customer Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [StoreController::class, 'myOrders'])->name('store.myOrders');
    Route::post('/sales/{sale}/request-cancellation', [SaleController::class, 'requestCancellation'])->name('sales.requestCancellation');
});

// ==========================================
// 2. AUTHENTICATION (SEPARATE ROLE PORTALS)
// ==========================================
// Portal 1: Login Pelanggan (Customer)
Route::get('/login', [AuthController::class, 'showLoginPelanggan'])->name('login');
Route::post('/login', [AuthController::class, 'loginPelanggan'])->name('login.pelanggan.submit');
Route::get('/login/pelanggan', [AuthController::class, 'showLoginPelanggan'])->name('login.pelanggan');

// Portal 2: Login Admin / Staff Operasional
Route::get('/login/admin', [AuthController::class, 'showLoginAdmin'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin.submit');
Route::get('/login/staff', [AuthController::class, 'showLoginAdmin'])->name('login.staff');
Route::post('/login/staff', [AuthController::class, 'loginAdmin'])->name('login.staff.submit');

// Portal 3: Login Owner (Executive Suite)
Route::get('/login/owner', [AuthController::class, 'showLoginOwner'])->name('login.owner');
Route::post('/login/owner', [AuthController::class, 'loginOwner'])->name('login.owner.submit');

// Member Registration & Logout
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 3. BACKOFFICE AREA (STAFF & OWNER ONLY)
// ==========================================
Route::middleware(['auth', 'role:owner,staff'])->group(function () {
    // Shared Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data: Products, Categories, Suppliers
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    // Stock-In (Purchases)
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    // Sales (POS)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
});

// ==========================================
// 4. OWNER EXCLUSIVE AREA (ROLE: OWNER ONLY)
// ==========================================
Route::middleware(['auth', 'role:owner'])->group(function () {
    // User & Staff Management
    Route::resource('users', UserController::class);

    // Order Cancellation Confirmation (Exclusively Owner)
    Route::post('/owner/sales/{sale}/confirm-cancellation', [DashboardController::class, 'confirmCancellation'])->name('owner.sales.confirmCancellation');
    Route::post('/owner/sales/{sale}/reject-cancellation', [DashboardController::class, 'rejectCancellation'])->name('owner.sales.rejectCancellation');
});

// ==========================================
// 5. SCREENSHOT PREVIEW ROUTES (FOR README GENERATION)
// ==========================================
Route::get('/screenshot/product', function () {
    $product = \App\Models\Product::first();
    return app(\App\Http\Controllers\StoreController::class)->show($product->slug);
});

Route::get('/screenshot/cart', function () {
    $pelanggan = \App\Models\User::where('role', 'pelanggan')->first();
    \Illuminate\Support\Facades\Auth::login($pelanggan);
    $product = \App\Models\Product::first();
    session()->put('cart', [
        $product->id => [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'selling_price' => (float) $product->selling_price,
            'quantity' => 1,
            'image' => $product->image,
            'color' => $product->color,
            'specs' => $product->specs,
        ]
    ]);
    return app(\App\Http\Controllers\StoreController::class)->viewCart();
});

Route::get('/screenshot/403', function () {
    return response()->view('errors.forbidden', [
        'title' => 'Akses Terlarang: Salah Portal Login',
        'userRole' => 'pelanggan',
        'requiredRoles' => ['owner'],
        'message' => 'Akun Anda terdaftar sebagai Pelanggan. Portal ini memiliki otorisasi eksekutif penuh dan hanya dapat diakses oleh Pemilik Bisnis (Owner).',
        'correctPortalUrl' => route('login.owner'),
        'correctPortalLabel' => 'Buka Portal Khusus Owner',
    ], 403);
});

Route::get('/screenshot/dashboard-owner', function () {
    $owner = \App\Models\User::where('role', 'owner')->first();
    \Illuminate\Support\Facades\Auth::login($owner);
    return app(\App\Http\Controllers\DashboardController::class)->index();
});

Route::get('/screenshot/pos', function () {
    $staff = \App\Models\User::where('role', 'staff')->first();
    \Illuminate\Support\Facades\Auth::login($staff);
    return app(\App\Http\Controllers\SaleController::class)->create();
});

Route::get('/screenshot/purchases', function () {
    $staff = \App\Models\User::where('role', 'staff')->first();
    \Illuminate\Support\Facades\Auth::login($staff);
    return app(\App\Http\Controllers\PurchaseController::class)->index(request());
});
