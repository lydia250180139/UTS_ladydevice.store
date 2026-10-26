<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Terlarang | ladydevice.store</title>
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush: {
                            50: '#fdf2f4',
                            100: '#fbe8ec',
                            200: '#f7d3db',
                            300: '#f0b0bf',
                            400: '#e5829b',
                            500: '#d75a7c',
                            600: '#c13f63',
                            700: '#a22f4f',
                            800: '#862943',
                            900: '#71263c',
                        },
                        techslate: '#0f172a'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800">
    
    @php
        $currentRole = auth()->user()?->role ?? $userRole ?? $detectedRole ?? 'Tidak Diketahui';
        $rolesList = (array)($requiredRoles ?? ['Khusus Otorisasi']);
    @endphp

    <div class="max-w-lg w-full bg-white/90 backdrop-blur-xl border border-rose-200/80 rounded-3xl p-8 md:p-10 shadow-2xl text-center relative overflow-hidden">
        <!-- Aesthetic background blur spot -->
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-rose-300/30 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-purple-300/30 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Lock Icon with Glow -->
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-rose-100 text-rose-500 mb-6 shadow-inner ring-8 ring-rose-50 animate-pulse">
            <i class="fa-solid fa-shield-halved text-4xl"></i>
        </div>

        <div class="inline-block px-3.5 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-rose-100 text-rose-700 border border-rose-300 mb-3">
            Status 403: Akses Terlarang
        </div>

        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 font-serif tracking-tight">
            {{ $title ?? 'Akses Terlarang!' }}
        </h1>
        
        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
            {{ $message ?? 'Halaman atau portal ini tidak sesuai dengan hak akses akun Anda. Sistem keamanan ladydevice.store memisahkan portal login antara Owner, Staff, dan Pelanggan untuk menjaga integritas data.' }}
        </p>

        <!-- Role Badge Card -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 text-left text-xs space-y-2">
            <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                <span class="text-slate-500 font-medium">Role Akun Terdeteksi:</span>
                <span class="font-bold px-2.5 py-0.5 rounded-full uppercase 
                    {{ $currentRole === 'owner' ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($currentRole === 'staff' ? 'bg-purple-100 text-purple-800 border border-purple-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300') }}">
                    <i class="fa-solid fa-user-tag mr-1"></i> {{ strtoupper($currentRole) }}
                </span>
            </div>
            <div class="flex justify-between items-center text-slate-500">
                <span>Hak Akses Halaman Ini:</span>
                <span class="font-mono text-slate-700 font-bold uppercase">
                    {{ implode(', ', $rolesList) }}
                </span>
            </div>
        </div>

        <!-- Smart Navigation Buttons based on Role & Context -->
        <div class="space-y-3">
            @if(isset($correctPortalUrl))
                <a href="{{ $correctPortalUrl }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-white font-bold text-xs uppercase tracking-wider bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-right-to-bracket"></i> {{ $correctPortalLabel ?? 'Buka Portal Login yang Sesuai' }}
                </a>
            @elseif(auth()->check())
                @if(auth()->user()->isPelanggan())
                    <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-white font-semibold bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 text-xs">
                        <i class="fa-solid fa-bag-shopping"></i> Kembali ke Toko LadyDevice
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-white font-semibold bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 text-xs">
                        <i class="fa-solid fa-chart-pie"></i> Buka Dashboard Saya
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-slate-600 font-medium hover:bg-slate-100 transition-all text-xs">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout & Masuk dengan Akun Lain
                    </button>
                </form>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                    <a href="{{ route('login') }}" class="p-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-800 font-semibold transition-colors flex items-center justify-center gap-1">
                        <i class="fa-solid fa-bag-shopping text-rose-600"></i> Pelanggan
                    </a>
                    <a href="{{ route('login.admin') }}" class="p-2.5 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 font-semibold transition-colors flex items-center justify-center gap-1">
                        <i class="fa-solid fa-user-shield text-purple-600"></i> Staff / Admin
                    </a>
                    <a href="{{ route('login.owner') }}" class="p-2.5 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold transition-colors flex items-center justify-center gap-1">
                        <i class="fa-solid fa-crown text-amber-600"></i> Owner
                    </a>
                </div>

                <a href="{{ route('home') }}" class="inline-block mt-2 text-xs text-slate-500 hover:text-rose-600">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda Toko
                </a>
            @endif
        </div>

        <div class="mt-8 pt-4 border-t border-slate-200/60 text-[11px] text-slate-400">
            ladydevice.store &bull; Sistem Inventaris & Butik Laptop Digital Estetik
        </div>
    </div>

</body>
</html>