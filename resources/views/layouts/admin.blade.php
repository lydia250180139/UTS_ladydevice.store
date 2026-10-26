<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Manajemen - ladydevice.store')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                        },
                        darkslate: '#0f172a'
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
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-lg lg:shadow-none">
        
        <!-- Sidebar Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100 bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-rose-200">
                    <i class="fa-solid fa-laptop text-base"></i>
                </div>
                <div>
                    <span class="font-serif text-xl font-bold bg-gradient-to-r from-rose-600 to-purple-700 bg-clip-text text-transparent">ladydevice</span><span class="text-rose-500 font-extrabold text-sm">.store</span>
                    <p class="text-[10px] tracking-wider uppercase font-semibold text-slate-400 -mt-1">Inventory & POS System</p>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Current User Role Card -->
        <div class="p-4 mx-4 my-3 rounded-2xl bg-gradient-to-br from-rose-50/70 to-purple-50/70 border border-rose-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-rose-400 to-pink-500 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full {{ auth()->user()->isOwner() ? 'bg-amber-500' : 'bg-purple-500' }}"></span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">
                        {{ auth()->user()->role === 'owner' ? 'Owner / Pemilik' : 'Staff Admin' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-6 text-xs">
            
            <!-- Group 1: Overview -->
            <div>
                <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Ikhtisar & Metrik</div>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard Terpadu</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Transaksi -->
            <div>
                <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Transaksi Bisnis</div>
                <div class="space-y-1">
                    <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('sales.*') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-cash-register w-4 text-center"></i>
                        <span>Penjualan / POS</span>
                    </a>
                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('purchases.*') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-truck-ramp-box w-4 text-center"></i>
                        <span>Pembelian (Stock-In)</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Master Data -->
            <div>
                <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Master Data</div>
                <div class="space-y-1">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('products.*') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-laptop w-4 text-center"></i>
                        <span>Katalog Laptop</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center"></i>
                        <span>Kategori Produk</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('suppliers.*') ? 'bg-rose-500 text-white shadow-md shadow-rose-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-building w-4 text-center"></i>
                        <span>Pemasok (Suppliers)</span>
                    </a>
                </div>
            </div>

            <!-- Group 4: Khusus Owner (Protected Area) -->
            @if(auth()->user()->isOwner())
                <div>
                    <div class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-amber-600 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-amber-500"></i> Area Khusus Owner
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'text-slate-600 hover:bg-amber-50 hover:text-amber-900' }}">
                            <i class="fa-solid fa-users-gear w-4 text-center"></i>
                            <span>Manajemen Pengguna & Staff</span>
                        </a>
                        <a href="{{ route('dashboard') }}#cancellation-section" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-rose-50 hover:text-rose-700 transition-all">
                            <i class="fa-solid fa-ban w-4 text-center text-rose-500"></i>
                            <span>Konfirmasi Pembatalan</span>
                        </a>
                    </div>
                </div>
            @endif

        </nav>

        <!-- Sidebar Bottom: Toko Online & Logout -->
        <div class="p-4 border-t border-slate-100 bg-slate-50 space-y-2 text-xs">
            <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white border border-slate-200 hover:border-rose-300 text-slate-700 font-semibold shadow-sm transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square text-rose-500"></i>
                <span>Lihat Toko Online</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-rose-600 hover:bg-rose-100/50 font-semibold transition-all">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-8 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard Pengelola')</h1>
                    <p class="text-xs text-slate-400">@yield('page_subtitle', 'Sistem Manajemen Stok & Penjualan Terintegrasi')</p>
                </div>
            </div>

            <!-- Topbar Right Items -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span class="font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition-all">
                    <i class="fa-solid fa-store"></i> Toko ladydevice.store
                </a>
            </div>
        </header>

        <!-- Flash Messages in Admin Panel -->
        <div class="px-4 sm:px-8 mt-4">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
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
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-xs p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700 text-xs p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
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

        <!-- Dynamic Body Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
