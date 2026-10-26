<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data for ladydevice.store
     */
    public function run(): void
    {
        // 1. SEED USERS (Owner, Staff, Pelanggan)
        $owner = User::updateOrCreate(
            ['email' => 'owner@ladydevice.store'],
            [
                'name' => 'Clarissa Putri (Owner)',
                'phone' => '081288889999',
                'role' => 'owner',
                'password' => Hash::make('password'),
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'staff@ladydevice.store'],
            [
                'name' => 'Adinda Zahra (Staff Admin)',
                'phone' => '081277776666',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ]
        );

        $customer1 = User::updateOrCreate(
            ['email' => 'pelanggan@ladydevice.store'],
            [
                'name' => 'Nabila Salsabila (Customer)',
                'phone' => '081255554444',
                'role' => 'pelanggan',
                'password' => Hash::make('password'),
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'siti.aulia@gmail.com'],
            [
                'name' => 'Siti Aulia Maharani',
                'phone' => '081399881122',
                'role' => 'pelanggan',
                'password' => Hash::make('password'),
            ]
        );

        // 2. SEED CATEGORIES
        $categoriesData = [
            [
                'name' => 'Ultrabook & Slim',
                'slug' => 'ultrabook-slim',
                'icon' => 'fa-feather',
                'description' => 'Laptop tipis, ringan, dan baterai tahan lama untuk produktivitas wanita karir modern.'
            ],
            [
                'name' => 'MacBook Ecosystem',
                'slug' => 'macbook-ecosystem',
                'icon' => 'fa-apple-whole',
                'description' => 'Desain minimalis berkelas premium dengan Apple Silicon Chip yang hening & powerful.'
            ],
            [
                'name' => '2-in-1 Convertible',
                'slug' => '2-in-1-convertible',
                'icon' => 'fa-tablet-screen-button',
                'description' => 'Layar sentuh fleksibel 360 derajat dengan dukungan stylus pen untuk presentasi & sketsa.'
            ],
            [
                'name' => 'Creator & Design Studio',
                'slug' => 'creator-design-studio',
                'icon' => 'fa-palette',
                'description' => 'Layar OLED 100% DCI-P3 akurat untuk content creator, ilustrator, dan video editor.'
            ],
            [
                'name' => 'Aesthetic Gaming',
                'slug' => 'aesthetic-gaming',
                'icon' => 'fa-gamepad',
                'description' => 'Performa gaming kencang dengan balutan warna pastel putih, mint, dan lilac yang cantik.'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. SEED SUPPLIERS
        $suppliersData = [
            [
                'name' => 'PT Global Tech Nusantara',
                'phone' => '021-55667788',
                'email' => 'distribution@globaltech.co.id',
                'address' => 'Kawasan Industri Gandaria Blok C-12, Jakarta Selatan'
            ],
            [
                'name' => 'CV Prima Digital Mandiri',
                'phone' => '031-88992211',
                'email' => 'sales@primadigital.id',
                'address' => 'Ruko Grand City No. 45, Gubeng, Surabaya'
            ],
            [
                'name' => 'PT Sinar Komputindo Jaya',
                'phone' => '022-77221199',
                'email' => 'order@sinarkomputindo.co.id',
                'address' => 'Jl. Asia Afrika No. 120, Lengkong, Bandung'
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sup) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        // 4. SEED PRODUCTS
        $productsData = [
            [
                'category_id' => $categories['macbook-ecosystem']->id,
                'sku' => 'LPT-APL-M2-STR',
                'name' => 'MacBook Air M2 13.6" Starlight',
                'slug' => 'macbook-air-m2-starlight',
                'purchase_price' => 14500000,
                'selling_price' => 16899000,
                'stock' => 14,
                'minimum_stock' => 3,
                'color' => 'Starlight Gold',
                'specs' => 'Apple M2 Chip 8-Core CPU 8-Core GPU | 8GB Unified Memory | 256GB SSD | 13.6" Liquid Retina 500 nits | MagSafe 3 | Backlit Magic Keyboard | Bobot 1.24 kg',
                'description' => 'MacBook Air berdesain elegan dalam balutan warna Starlight bernuansa emas lembut. Sangat ramping dan tenang tanpa kipas pendingin.',
                'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['macbook-ecosystem']->id,
                'sku' => 'LPT-APL-M3-MDN',
                'name' => 'MacBook Air M3 15" Midnight Space',
                'slug' => 'macbook-air-m3-midnight',
                'purchase_price' => 18000000,
                'selling_price' => 21499000,
                'stock' => 8,
                'minimum_stock' => 2,
                'color' => 'Midnight Blue Deep',
                'specs' => 'Apple M3 Chip 8-Core CPU 10-Core GPU | 16GB Unified Memory | 512GB SSD | 15.3" Liquid Retina | 6 Speaker Audio System | Baterai hingga 18 jam',
                'description' => 'Layar 15 inci yang lega dengan finish anodized seal untuk mengurangi sidik jari. Partner terbaik editing estetis dan multitasking.',
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['ultrabook-slim']->id,
                'sku' => 'LPT-ASU-ZB14-LLC',
                'name' => 'ASUS Zenbook 14 OLED Lilac Mist',
                'slug' => 'asus-zenbook-14-oled-lilac',
                'purchase_price' => 13200000,
                'selling_price' => 15799000,
                'stock' => 15,
                'minimum_stock' => 4,
                'color' => 'Lilac Mist Pastel',
                'specs' => 'Intel Core Ultra 7 155H AI Boost | 16GB LPDDR5X | 1TB NVMe Gen4 | 14" 2.8K 120Hz Lumina OLED HDR 600 nits | Harmon Kardon Audio | 1.2 kg',
                'description' => 'Nuansa pastel ungu lilac mist yang sangat memikat hati dengan layar OLED paling tajam dan memanjakan mata.',
                'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['ultrabook-slim']->id,
                'sku' => 'LPT-ASU-S13-GRY',
                'name' => 'ASUS Zenbook S 13 OLED Basalt Gray',
                'slug' => 'asus-zenbook-s-13-basalt',
                'purchase_price' => 17000000,
                'selling_price' => 20199000,
                'stock' => 6,
                'minimum_stock' => 2,
                'color' => 'Basalt Gray Eco',
                'specs' => 'Intel Core Ultra 7 155U | 32GB LPDDR5X | 1TB PCIe 4.0 SSD | 13.3" 2.8K 16:10 OLED | Bobot 1.0 kg & Tebal 1 cm | Plasma Ceramic Aluminium',
                'description' => 'Laptop tertipis di dunia berbahan keramik plasma aluminium ramah lingkungan. Ringan seperti buku catatan.',
                'image' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['2-in-1-convertible']->id,
                'sku' => 'LPT-HP-SPX-BLU',
                'name' => 'HP Spectre x360 14 Nocturne Blue',
                'slug' => 'hp-spectre-x360-14-nocturne',
                'purchase_price' => 21000000,
                'selling_price' => 24899000,
                'stock' => 5,
                'minimum_stock' => 2,
                'color' => 'Nocturne Blue Pale Rose Accent',
                'specs' => 'Intel Core Ultra 7 155H | 32GB LPDDR5x | 2TB Gen4 SSD | 14" 2.8K OLED Touch 120Hz IMAX Enhanced | HP Rechargeable MPP2.0 Tilt Pen include',
                'description' => 'Kemewahan sudut diamond-cut dengan warna biru malam dan aksen emas pucat. Dapat diputar 360 derajat untuk menggambar dan presentasi.',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['creator-design-studio']->id,
                'sku' => 'LPT-DEL-XPS-WHT',
                'name' => 'Dell XPS 13 Plus Frost White',
                'slug' => 'dell-xps-13-plus-frost-white',
                'purchase_price' => 22500000,
                'selling_price' => 26999000,
                'stock' => 4,
                'minimum_stock' => 2,
                'color' => 'Frost White Matte',
                'specs' => 'Intel Core i7-1360P 12-Core | 16GB LPDDR5 | 1TB NVMe SSD | 13.4" 3.5K OLED InfinityEdge Touch | Seamless Glass Touchpad & Capacitive Touch Function Row',
                'description' => 'Desain futuristik minimalis tanpa garis touchpad yang terlihat. Seluruh bodi terbuat dari kaca dan aluminium putih bersih.',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['ultrabook-slim']->id,
                'sku' => 'LPT-LNV-YG7-SEA',
                'name' => 'Lenovo Yoga Slim 7 Aura Seashell Cream',
                'slug' => 'lenovo-yoga-slim-7-aura',
                'purchase_price' => 14200000,
                'selling_price' => 16999000,
                'stock' => 10,
                'minimum_stock' => 3,
                'color' => 'Seashell Cream Pastel',
                'specs' => 'Intel Core Ultra 7 258V Lunar Lake | 32GB LPDDR5X Memory On Package | 1TB SSD | 15.3" 2.8K 120Hz PureSight Pro Display 500 nits | Baterai 70Wh',
                'description' => 'Laptop berdaya tahan baterai luar biasa hingga 22 jam dengan balutan warna krem kerang yang sangat lembut dan manis.',
                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['aesthetic-gaming']->id,
                'sku' => 'LPT-ASU-ROG-WHT',
                'name' => 'ROG Zephyrus G14 Moonlight White',
                'slug' => 'rog-zephyrus-g14-white',
                'purchase_price' => 25000000,
                'selling_price' => 29499000,
                'stock' => 5,
                'minimum_stock' => 2,
                'color' => 'Moonlight White CNC',
                'specs' => 'AMD Ryzen 9 8945HS AI Engine | NVIDIA GeForce RTX 4060 8GB GDDR6 | 16GB LPDDR5X | 1TB SSD | 14" 3K 120Hz ROG Nebula OLED | Slash Lighting White',
                'description' => 'Laptop gaming paling estetik dengan bodi unibody putih bersih dan strip lampu Slash LED elegan di bagian cover.',
                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['ultrabook-slim']->id,
                'sku' => 'LPT-MS-SF7-PLT',
                'name' => 'Microsoft Surface Laptop 7 Platinum',
                'slug' => 'microsoft-surface-laptop-7-platinum',
                'purchase_price' => 15500000,
                'selling_price' => 18499000,
                'stock' => 7,
                'minimum_stock' => 2,
                'color' => 'Platinum Silver',
                'specs' => 'Snapdragon X Elite 12-Core NPU 45 TOPS | 16GB RAM | 512GB SSD Removable | 13.8" PixelSense Flow 120Hz Touch | Copilot+ PC AI Native',
                'description' => 'Generasi AI PC terbaru dari Microsoft dengan keyboard tactile ternyaman dan layar sentuh rasio 3:2 yang produktif.',
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['ultrabook-slim']->id,
                'sku' => 'LPT-ACR-SWG-PNK',
                'name' => 'Acer Swift Go 14 Sakura Pink Edition',
                'slug' => 'acer-swift-go-14-sakura-pink',
                'purchase_price' => 9800000,
                'selling_price' => 11899000,
                'stock' => 2, // Low stock on purpose for testing dashboard low-stock alert!
                'minimum_stock' => 4,
                'color' => 'Sakura Pink Satin',
                'specs' => 'Intel Core i5-13500H 12-Core | 16GB LPDDR5 | 512GB NVMe SSD | 14" 2.8K 90Hz OLED 100% DCI-P3 | 1440p QHD Webcam with TNR | Bobot 1.25 kg',
                'description' => 'Edisi spesial warna Sakura Pink lembut dengan bodi aluminium satin dan webcam super jernih untuk online meeting.',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $prod) {
            $createdProducts[] = Product::firstOrCreate(['sku' => $prod['sku']], $prod);
        }

        // 5. SEED PURCHASES (Stock-In History)
        $p1 = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-' . date('Ymd') . '-0001'],
            [
                'supplier_id' => $suppliers[0]->id,
                'user_id' => $staff->id,
                'total' => 58000000,
                'status' => 'completed',
                'notes' => 'Restock awal kuartal MacBook Series resmi distributor.',
                'purchased_at' => Carbon::now()->subDays(6),
            ]
        );

        if (!$p1->items()->exists()) {
            PurchaseItem::create([
                'purchase_id' => $p1->id,
                'product_id' => $createdProducts[0]->id, // MacBook Air M2
                'quantity' => 2,
                'unit_cost' => 14500000,
                'subtotal' => 29000000,
            ]);

            PurchaseItem::create([
                'purchase_id' => $p1->id,
                'product_id' => $createdProducts[1]->id, // MacBook Air M3
                'quantity' => 1,
                'unit_cost' => 18000000,
                'subtotal' => 18000000,
            ]);
        }

        $p2 = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-' . date('Ymd') . '-0002'],
            [
                'supplier_id' => $suppliers[1]->id,
                'user_id' => $owner->id,
                'total' => 39600000,
                'status' => 'completed',
                'notes' => 'Restock laptop ASUS Zenbook OLED series.',
                'purchased_at' => Carbon::now()->subDays(3),
            ]
        );

        if (!$p2->items()->exists()) {
            PurchaseItem::create([
                'purchase_id' => $p2->id,
                'product_id' => $createdProducts[2]->id, // Zenbook Lilac
                'quantity' => 3,
                'unit_cost' => 13200000,
                'subtotal' => 39600000,
            ]);
        }

        // 6. SEED SALES (Completed Sales & 1 Pending Cancellation for Owner Testing)
        // Sale 1: Completed online customer purchase
        $s1 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0001'],
            [
                'user_id' => $customer1->id,
                'customer_name' => 'Nabila Salsabila',
                'customer_phone' => '081255554444',
                'shipping_address' => 'Apartemen Sudirman Tower A No. 12B, Jakarta Pusat',
                'notes' => 'Packing kayu dan bubble wrap tebal ya kak.',
                'total' => 16899000,
                'status' => 'completed',
                'sold_at' => Carbon::now()->subDays(5),
            ]
        );
        if (!$s1->items()->exists()) {
            SaleItem::create([
                'sale_id' => $s1->id,
                'product_id' => $createdProducts[0]->id,
                'quantity' => 1,
                'unit_price' => 16899000,
                'subtotal' => 16899000,
            ]);
        }

        // Sale 2: Completed walk-in purchase processed by Staff
        $s2 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0002'],
            [
                'user_id' => $staff->id,
                'customer_name' => 'dr. Jessica Tania',
                'customer_phone' => '081722334455',
                'shipping_address' => 'Ambil langsung di toko LadyDevice Boutique',
                'notes' => 'Pembelian langsung kasir toko.',
                'total' => 31598000,
                'status' => 'completed',
                'sold_at' => Carbon::now()->subDays(3),
            ]
        );
        if (!$s2->items()->exists()) {
            SaleItem::create([
                'sale_id' => $s2->id,
                'product_id' => $createdProducts[2]->id, // Zenbook Lilac
                'quantity' => 2,
                'unit_price' => 15799000,
                'subtotal' => 31598000,
            ]);
        }

        // Sale 3: Sale with status 'pending_cancellation' so the OWNER can test order cancellation immediately!
        $s3 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0003'],
            [
                'user_id' => $customer2->id,
                'customer_name' => 'Siti Aulia Maharani',
                'customer_phone' => '081399881122',
                'shipping_address' => 'Perumahan Pondok Hijau Blok D2 No. 8, Bandung',
                'notes' => 'Tolong batalkan pesanan ini karena salah memilih warna.',
                'total' => 24899000,
                'status' => 'pending_cancellation',
                'cancellation_reason' => 'Pelanggan keliru memilih varian warna HP Spectre x360, ingin mengganti ke warna lain.',
                'cancellation_requested_at' => Carbon::now()->subHours(4),
                'sold_at' => Carbon::now()->subHours(6),
            ]
        );
        if (!$s3->items()->exists()) {
            SaleItem::create([
                'sale_id' => $s3->id,
                'product_id' => $createdProducts[4]->id, // HP Spectre
                'quantity' => 1,
                'unit_price' => 24899000,
                'subtotal' => 24899000,
            ]);
        }

        // Sale 4: Another completed sale yesterday for nice chart curves
        $s4 = Sale::firstOrCreate(
            ['sale_number' => 'INV-' . date('Ymd') . '-0004'],
            [
                'user_id' => $customer1->id,
                'customer_name' => 'Nabila Salsabila',
                'customer_phone' => '081255554444',
                'shipping_address' => 'Apartemen Sudirman Tower A No. 12B, Jakarta Pusat',
                'notes' => 'Kado wisuda adik tercinta.',
                'total' => 29499000,
                'status' => 'completed',
                'sold_at' => Carbon::yesterday(),
            ]
        );
        if (!$s4->items()->exists()) {
            SaleItem::create([
                'sale_id' => $s4->id,
                'product_id' => $createdProducts[7]->id, // ROG White
                'quantity' => 1,
                'unit_price' => 29499000,
                'subtotal' => 29499000,
            ]);
        }
    }
}
