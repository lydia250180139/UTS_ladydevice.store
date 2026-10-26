<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySalesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('ladydevice.store');
        $response->assertSee('Owner');
        $response->assertSee('Admin / Staff');
        $response->assertSee('Pelanggan');
    }

    public function test_owner_can_login_and_access_dashboard(): void
    {
        $owner = User::where('email', 'owner@ladydevice.store')->first();

        $response = $this->actingAs($owner)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Terpadu Bisnis');
        $response->assertSee('Hak Akses Eksklusif Owner');
    }

    public function test_staff_cannot_access_owner_exclusive_user_management(): void
    {
        $staff = User::where('email', 'staff@ladydevice.store')->first();

        // Staff tries to access /users
        $response = $this->actingAs($staff)->get('/users');
        $response->assertStatus(403);
        $response->assertSee('Akses Terlarang');
    }

    public function test_pelanggan_cannot_access_backoffice_dashboard(): void
    {
        $customer = User::where('email', 'pelanggan@ladydevice.store')->first();

        // Customer tries to access /dashboard
        $response = $this->actingAs($customer)->get('/dashboard');
        $response->assertStatus(403);
        $response->assertSee('Akses Terlarang');
    }

    public function test_sale_with_insufficient_stock_is_rejected(): void
    {
        $staff = User::where('email', 'staff@ladydevice.store')->first();
        $product = Product::first();

        $initialStock = $product->stock;
        $overQuantity = $initialStock + 10;

        $response = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Budi Santoso',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $overQuantity,
                ]
            ]
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertEquals($initialStock, $product->fresh()->stock);
    }

    public function test_owner_cancellation_restores_stock(): void
    {
        $owner = User::where('email', 'owner@ladydevice.store')->first();
        $pendingSale = Sale::where('status', 'pending_cancellation')->first();

        $this->assertNotNull($pendingSale);
        $saleItem = $pendingSale->items->first();
        $product = $saleItem->product;
        $stockBefore = $product->stock;

        $response = $this->actingAs($owner)->post("/owner/sales/{$pendingSale->id}/confirm-cancellation", [
            'cancellation_reason' => 'Disetujui oleh Owner'
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $pendingSale->fresh()->status);
        $this->assertEquals($stockBefore + $saleItem->quantity, $product->fresh()->stock);
    }

    public function test_guest_cannot_add_to_cart_without_login_as_pelanggan(): void
    {
        $product = Product::first();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('warning');
    }

    public function test_pelanggan_can_checkout_with_qris(): void
    {
        $customer = User::where('email', 'pelanggan@ladydevice.store')->first();
        $product = Product::where('stock', '>', 2)->first();

        // 1. Add to cart as pelanggan
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // 2. Checkout with QRIS
        $response = $this->actingAs($customer)->post('/checkout', [
            'customer_name' => 'Nabila Salsabila',
            'customer_phone' => '081255554444',
            'shipping_address' => 'Apartemen Sudirman Tower A',
            'payment_method' => 'qris',
            'notes' => 'Pembayaran via QRIS',
        ]);

        $this->assertDatabaseHas('sales', [
            'customer_name' => 'Nabila Salsabila',
            'payment_method' => 'qris',
        ]);
    }

    public function test_separate_login_portals_render_successfully(): void
    {
        // 1. Pelanggan portal
        $resPelanggan = $this->get('/login');
        $resPelanggan->assertStatus(200);
        $resPelanggan->assertSee('Portal Khusus Pelanggan');

        // 2. Admin / Staff portal
        $resAdmin = $this->get('/login/admin');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Portal Khusus Admin / Staff');

        // 3. Owner portal
        $resOwner = $this->get('/login/owner');
        $resOwner->assertStatus(200);
        $resOwner->assertSee('Portal Pemilik (Owner)');
    }

    public function test_cross_portal_login_is_strictly_rejected_with_403_forbidden(): void
    {
        // A. Pelanggan tries to login at Admin portal -> 403 Forbidden
        $res1 = $this->post('/login/admin', [
            'email' => 'pelanggan@ladydevice.store',
            'password' => 'password',
        ]);
        $res1->assertStatus(403);
        $res1->assertSee('Akses Terlarang: Bukan Akun Admin / Staff');

        // B. Pelanggan tries to login at Owner portal -> 403 Forbidden
        $res2 = $this->post('/login/owner', [
            'email' => 'pelanggan@ladydevice.store',
            'password' => 'password',
        ]);
        $res2->assertStatus(403);
        $res2->assertSee('Akses Terlarang: Bukan Akun Owner');

        // C. Staff tries to login at Pelanggan portal -> 403 Forbidden
        $res3 = $this->post('/login', [
            'email' => 'staff@ladydevice.store',
            'password' => 'password',
        ]);
        $res3->assertStatus(403);
        $res3->assertSee('Akses Terlarang: Salah Portal Login');

        // D. Staff tries to login at Owner portal -> 403 Forbidden
        $res4 = $this->post('/login/owner', [
            'email' => 'staff@ladydevice.store',
            'password' => 'password',
        ]);
        $res4->assertStatus(403);
        $res4->assertSee('Akses Terlarang: Bukan Akun Owner');

        // E. Owner tries to login at Pelanggan portal -> 403 Forbidden
        $res5 = $this->post('/login', [
            'email' => 'owner@ladydevice.store',
            'password' => 'password',
        ]);
        $res5->assertStatus(403);
        $res5->assertSee('Akses Terlarang: Salah Portal Login');

        // F. Owner tries to login at Admin portal -> 403 Forbidden
        $res6 = $this->post('/login/admin', [
            'email' => 'owner@ladydevice.store',
            'password' => 'password',
        ]);
        $res6->assertStatus(403);
        $res6->assertSee('Akses Terlarang: Bukan Akun Admin / Staff');
    }

    public function test_each_role_can_login_at_their_own_portal(): void
    {
        // 1. Pelanggan login at Pelanggan portal -> redirect home
        $resPelanggan = $this->post('/login', [
            'email' => 'pelanggan@ladydevice.store',
            'password' => 'password',
        ]);
        $resPelanggan->assertRedirect(route('home'));
        $this->assertAuthenticated();
        auth()->logout();

        // 2. Staff login at Admin portal -> redirect dashboard
        $resStaff = $this->post('/login/admin', [
            'email' => 'staff@ladydevice.store',
            'password' => 'password',
        ]);
        $resStaff->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        auth()->logout();

        // 3. Owner login at Owner portal -> redirect dashboard
        $resOwner = $this->post('/login/owner', [
            'email' => 'owner@ladydevice.store',
            'password' => 'password',
        ]);
        $resOwner->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }
}
