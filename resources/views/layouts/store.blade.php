<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ladydevice.store - Toko Laptop Estetik & Kekinian')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js CDN for clean micro-interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        },
                        pastel: {
                            purple: '#f5f3ff',
                            rose: '#fff1f2',
                            pink: '#fdf2f8',
                            lavender: '#ede9fe',
                            softdark: '#0f172a'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-rose-50/40 via-white to-purple-50/40 min-h-screen flex flex-col font-sans text-slate-800 antialiased selection:bg-rose-200 selection:text-rose-900">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-rose-600 via-pink-600 to-purple-600 text-white text-xs font-medium py-2 px-4 text-center tracking-wide">
        <span class="inline-flex items-center gap-2">
            <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">Aesthetic Tech</span>
            🌸 Toko Laptop Estetik ladydevice.store &bull; Garansi Resmi Indonesia &bull; Bebas Ongkir se-Indonesia
        </span>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 glassmorphism border-b border-rose-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-rose-200 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-laptop text-lg"></i>
                    </div>
                    <div>
                        <span class="font-serif text-2xl font-bold bg-gradient-to-r from-rose-600 to-purple-700 bg-clip-text text-transparent">ladydevice</span><span class="text-rose-500 font-extrabold text-lg">.store</span>
                        <p class="text-[10px] tracking-wider uppercase font-semibold text-slate-400 -mt-1">Aesthetic Laptop Boutique</p>
                    </div>
                </a>

                <!-- Desktop Search Bar -->
                <form action="{{ route('home') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari MacBook, Zenbook Lilac, XPS Frost..." 
                            class="w-full bg-slate-50 border border-rose-200/80 rounded-full pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white transition-all shadow-inner">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                </form>

                <!-- Right Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    <!-- Cart Button with Counter -->
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-2xl bg-white border border-rose-200/80 text-slate-700 hover:text-rose-600 hover:border-rose-400 transition-all shadow-sm flex items-center gap-2 group">
                        <i class="fa-solid fa-bag-shopping text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="hidden sm:inline text-xs font-semibold">Keranjang</span>
                        @if(($cartCount ?? 0) > 0)
                            <span class="bg-rose-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <!-- If Owner or Staff, quick link to Backoffice Dashboard -->
                        @if(auth()->user()->isOwner() || auth()->user()->isStaff())
                            <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-purple-100 text-purple-800 hover:bg-purple-200 border border-purple-300 transition-colors">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Panel {{ ucfirst(auth()->user()->role) }}</span>
                            </a>
                        @endif

                        <!-- Customer My Orders -->
                        <a href="{{ route('store.myOrders') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                            <i class="fa-solid fa-receipt text-rose-500"></i>
                            <span>Pesanan Saya</span>
                        </a>

                        <!-- User Profile Pill -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 sm:px-3 sm:py-2 rounded-2xl bg-white border border-slate-200 hover:border-rose-300 transition-all shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-rose-400 to-pink-500 text-white font-bold text-xs flex items-center justify-center">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left text-xs leading-tight">
                                    <div class="font-bold text-slate-800 max-w-[100px] truncate">{{ auth()->user()->name }}</div>
                                    <div class="text-[10px] text-rose-600 font-semibold uppercase">{{ auth()->user()->role }}</div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-rose-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <div class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</div>
                                    <span class="inline-block mt-1 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ auth()->user()->role === 'owner' ? 'bg-amber-100 text-amber-800' : (auth()->user()->role === 'staff' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800') }}">
                                        {{ auth()->user()->role }}
                                    </span>
                                </div>

                                @if(auth()->user()->isOwner() || auth()->user()->isStaff())
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-purple-700 font-semibold hover:bg-purple-50">
                                        <i class="fa-solid fa-chart-line w-4"></i> Dashboard Pengelola
                                    </a>
                                @endif

                                <a href="{{ route('store.myOrders') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-700 hover:bg-rose-50">
                                    <i class="fa-solid fa-receipt w-4 text-rose-500"></i> Riwayat Pesanan
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="border-t border-slate-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-medium text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Guest Login Dropdown / Register -->
                        <div class="flex items-center gap-2">
                            <div x-data="{ openLogin: false }" class="relative">
                                <button @click="openLogin = !openLogin" class="text-xs font-bold px-3.5 py-2 rounded-xl text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition-all flex items-center gap-1.5 border border-transparent hover:border-rose-200">
                                    <i class="fa-solid fa-right-to-bracket text-rose-500"></i>
                                    <span>Masuk</span>
                                    <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                                </button>
                                
                                <div x-show="openLogin" @click.away="openLogin = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-rose-100 py-2 z-50">
                                    <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                        Pilih Portal Login:
                                    </div>
                                    <a href="{{ route('login') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-rose-50 hover:text-rose-700">
                                        <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-[11px]">
                                            <i class="fa-solid fa-bag-shopping"></i>
                                        </div>
                                        <div>
                                            <div class="leading-tight font-bold">Portal Pelanggan</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Katalog & Checkout</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('login.admin') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-purple-50 hover:text-purple-700">
                                        <div class="w-6 h-6 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-[11px]">
                                            <i class="fa-solid fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <div class="leading-tight font-bold">Portal Admin / Staff</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Kasir POS & Stok Masuk</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('login.owner') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-amber-50 hover:text-amber-700">
                                        <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-[11px]">
                                            <i class="fa-solid fa-crown"></i>
                                        </div>
                                        <div>
                                            <div class="leading-tight font-bold">Portal Owner</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Otorisasi & Laporan</div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('register') }}" class="text-xs font-bold px-4 py-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white shadow-md shadow-rose-200 transition-all transform hover:-translate-y-0.5">
                                Daftar Member
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </header>

    <!-- Global Notification Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-sm flex items-center justify-between shadow-md ring-2 ring-rose-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-200 text-rose-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <strong class="font-bold">Pemberitahuan Sistem:</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <span>{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-info"></i>
                    </div>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Aesthetic Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 mt-20 border-t border-rose-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-laptop"></i>
                        </div>
                        <span class="font-serif text-xl font-bold text-white">ladydevice<span class="text-rose-400">.store</span></span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Boutique kurasi laptop estetik dan berdaya tinggi untuk perempuan karir, kreator digital, mahasiswi, dan profesional modern di Indonesia.
                    </p>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Col 2: Kategori Laptop -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 tracking-wider uppercase font-sans">Kategori Laptop</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('home', ['category' => 'macbook-ecosystem']) }}" class="hover:text-rose-400 transition-colors">MacBook Ecosystem</a></li>
                        <li><a href="{{ route('home', ['category' => 'ultrabook-slim']) }}" class="hover:text-rose-400 transition-colors">Ultrabook & Slim Series</a></li>
                        <li><a href="{{ route('home', ['category' => '2-in-1-convertible']) }}" class="hover:text-rose-400 transition-colors">2-in-1 Touchscreen</a></li>
                        <li><a href="{{ route('home', ['category' => 'creator-design-studio']) }}" class="hover:text-rose-400 transition-colors">Creator OLED Studio</a></li>
                        <li><a href="{{ route('home', ['category' => 'aesthetic-gaming']) }}" class="hover:text-rose-400 transition-colors">Aesthetic Gaming Pastels</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jaminan Layanan -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 tracking-wider uppercase font-sans">Jaminan Layanan</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-certificate text-rose-400"></i> Garansi Resmi 100% Original</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-box text-rose-400"></i> Packing Kayu & Asuransi Aman</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-headset text-rose-400"></i> Konsultasi Spesifikasi Gratis</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-rotate-left text-rose-400"></i> Pembatalan Transparan via Owner</li>
                    </ul>
                </div>

                <!-- Col 4: Demo Akun Akses UTS & Portal Login Terpisah -->
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 text-xs">
                    <h4 class="text-rose-400 font-bold mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-door-open"></i> Portal Login Sesuai Role
                    </h4>
                    <div class="space-y-2 text-slate-300 text-[11px]">
                        <a href="{{ route('login.owner') }}" class="block p-1.5 rounded-lg bg-slate-900/60 hover:bg-amber-950/40 border border-slate-700 hover:border-amber-500/50 transition-colors">
                            <div class="flex items-center justify-between text-amber-300 font-bold">
                                <span><i class="fa-solid fa-crown mr-1"></i> Portal Owner</span>
                                <span class="text-[9px] uppercase px-1.5 py-0.2 bg-amber-900/60 text-amber-200 rounded">Buka &rarr;</span>
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">owner@ladydevice.store</div>
                        </a>
                        <a href="{{ route('login.admin') }}" class="block p-1.5 rounded-lg bg-slate-900/60 hover:bg-purple-950/40 border border-slate-700 hover:border-purple-500/50 transition-colors">
                            <div class="flex items-center justify-between text-purple-300 font-bold">
                                <span><i class="fa-solid fa-user-shield mr-1"></i> Portal Admin / Staff</span>
                                <span class="text-[9px] uppercase px-1.5 py-0.2 bg-purple-900/60 text-purple-200 rounded">Buka &rarr;</span>
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">staff@ladydevice.store</div>
                        </a>
                        <a href="{{ route('login') }}" class="block p-1.5 rounded-lg bg-slate-900/60 hover:bg-rose-950/40 border border-slate-700 hover:border-rose-500/50 transition-colors">
                            <div class="flex items-center justify-between text-rose-300 font-bold">
                                <span><i class="fa-solid fa-bag-shopping mr-1"></i> Portal Pelanggan</span>
                                <span class="text-[9px] uppercase px-1.5 py-0.2 bg-rose-900/60 text-rose-200 rounded">Buka &rarr;</span>
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">pelanggan@ladydevice.store</div>
                        </a>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-slate-700 text-[10px] text-slate-400 flex items-center justify-between">
                        <span>Status 403 Jika Salah Portal</span>
                        <span class="text-rose-400 font-semibold">Role Enforcement</span>
                    </div>
                </div>

            </div>

            <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; 2026 ladydevice.store &bull; Web Programming 2 Integrated Laravel Project. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <span>Clean Architecture</span>
                    <span>&bull;</span>
                    <span>Atomic DB Transactions</span>
                    <span>&bull;</span>
                    <span>Role Enforcement</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
