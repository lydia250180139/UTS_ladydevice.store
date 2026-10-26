<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - ladydevice.store</title>
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
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
</head>
<body class="bg-gradient-to-br from-rose-100/70 via-pink-50 to-purple-100/60 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800 selection:bg-rose-200">

    <div class="max-w-md w-full bg-white/95 backdrop-blur-xl border border-rose-200/90 rounded-3xl p-6 sm:p-9 shadow-2xl relative overflow-hidden">
        
        <!-- Background decorative ambient circles -->
        <div class="absolute -top-14 -right-14 w-40 h-40 bg-rose-400/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-14 -left-14 w-40 h-40 bg-pink-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Top Badge -->
        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 shadow-sm">
                <i class="fa-solid fa-bag-shopping text-rose-500"></i> Portal Khusus Pelanggan
            </span>
        </div>

        <!-- Brand & Heading -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-1 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-rose-200 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-laptop text-base"></i>
                </div>
                <div class="text-left">
                    <span class="font-serif text-2xl font-bold bg-gradient-to-r from-rose-600 to-purple-700 bg-clip-text text-transparent">ladydevice</span><span class="text-rose-500 font-extrabold text-base">.store</span>
                </div>
            </a>
            <h2 class="text-xl font-bold text-slate-900 font-serif">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk belanja laptop estetik & cek riwayat pesanan Anda</p>
        </div>


        <!-- Global Flash Messages -->
        @if(session('error'))
            <div class="p-3 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-3 mb-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-center gap-2">
                <i class="fa-solid fa-info text-blue-600"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Login Form Pelanggan -->
        <form action="{{ route('login.pelanggan.submit') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf

            <!-- Email Field (Strictly Blank) -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Pelanggan *</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="" required placeholder="Masukkan email pelanggan..." 
                        autocomplete="off" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white transition-all @error('email') border-rose-500 @enderror">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field (Strictly Blank) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi (Password) *</label>
                </div>
                <div class="relative">
                    <input type="password" id="password" name="password" value="" required placeholder="Masukkan kata sandi..." 
                        autocomplete="new-password" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-10 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 text-xs">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-rose-600 border-slate-300 focus:ring-rose-400">
                    <span>Ingat akun saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sebagai Pelanggan
            </button>
        </form>

        <!-- Register Link for Customers -->
        <div class="mt-5 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Pelanggan baru? 
            <a href="{{ route('register') }}" class="font-bold text-rose-600 hover:text-rose-700 hover:underline">
                Daftar Akun Member
            </a>
            <span class="mx-2">&bull;</span>
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800">
                Kembali ke Toko
            </a>
        </div>

        <!-- Portal Switcher (Pilihan Login Berdasarkan Role) -->
        <div class="mt-5 pt-4 border-t border-slate-200/80 bg-slate-50/80 -mx-6 sm:-mx-9 -mb-6 sm:-mb-9 p-4 text-center">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2.5">
                Bukan Pelanggan? Pilih Portal Anda:
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ route('login.admin') }}" class="py-2 px-3 rounded-xl border border-purple-200 bg-white hover:bg-purple-50 text-purple-800 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-user-shield text-purple-600"></i> Portal Admin / Staff
                </a>
                <a href="{{ route('login.owner') }}" class="py-2 px-3 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 text-amber-800 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-crown text-amber-600"></i> Portal Owner
                </a>
            </div>
        </div>

    </div>

    <script>
        // Memastikan kolom input email dan kata sandi selalu bersih saat dibuka
        window.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            if (emailInput) emailInput.value = '';
            if (passInput) passInput.value = '';
        });

        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>