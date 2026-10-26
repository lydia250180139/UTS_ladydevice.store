<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Owner (Executive Suite) - ladydevice.store</title>
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
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
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
<body class="bg-gradient-to-br from-[#0c0d11] via-[#14161f] to-[#0c0d11] min-h-screen flex items-center justify-center p-4 font-sans text-slate-100 selection:bg-amber-500 selection:text-black">

    <div class="max-w-md w-full bg-[#181a24]/90 backdrop-blur-2xl border border-amber-500/40 rounded-3xl p-6 sm:p-9 shadow-2xl relative overflow-hidden ring-1 ring-amber-500/20">
        
        <!-- Ambient Luxurious Gold Lights -->
        <div class="absolute -top-14 -right-14 w-44 h-44 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-14 -left-14 w-44 h-44 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Badge -->
        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-950/80 text-amber-300 border border-amber-500/50 shadow-sm shadow-amber-900/50">
                <i class="fa-solid fa-crown text-amber-400"></i> Executive Owner Portal & Decision Suite
            </span>
        </div>

        <!-- Brand & Heading -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-2 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500 via-amber-600 to-rose-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-crown text-base"></i>
                </div>
                <div class="text-left">
                    <span class="font-serif font-bold text-2xl tracking-tight bg-gradient-to-r from-amber-200 via-rose-200 to-amber-400 bg-clip-text text-transparent">ladydevice</span><span class="text-amber-400 font-extrabold text-base">.store</span>
                </div>
            </a>
            <h2 class="text-xl font-bold text-white font-serif tracking-tight">Portal Pemilik (Owner)</h2>
            <p class="text-xs text-stone-400 mt-1">Otorisasi Pembatalan Transaksi, Analisis Penjualan & Hak Kelola Penuh</p>
        </div>


        <!-- Global Flash Messages -->
        @if(session('error'))
            <div class="p-3 mb-4 rounded-xl bg-rose-950/80 border border-rose-700/60 text-rose-200 text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 mb-4 rounded-xl bg-amber-950/80 border border-amber-700/60 text-amber-200 text-xs flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-400"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-3 mb-4 rounded-xl bg-blue-950/80 border border-blue-700/60 text-blue-200 text-xs flex items-center gap-2">
                <i class="fa-solid fa-info text-blue-400"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Form Login Owner -->
        <form action="{{ route('login.owner.submit') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf

            <!-- Email (Strictly Blank) -->
            <div>
                <label for="email" class="block text-xs font-bold text-amber-200/90 mb-1.5">Alamat Email Owner *</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="" required placeholder="owner@ladydevice.store..." 
                        autocomplete="off" 
                        class="w-full bg-black/60 border border-stone-700 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all @error('email') border-rose-500 @enderror">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-400/60 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password (Strictly Blank) -->
            <div>
                <label for="password" class="block text-xs font-bold text-amber-200/90 mb-1.5">Kata Sandi (Password) *</label>
                <div class="relative">
                    <input type="password" id="password" name="password" value="" required placeholder="Masukkan kata sandi owner..." 
                        autocomplete="new-password" 
                        class="w-full bg-black/60 border border-stone-700 rounded-2xl pl-10 pr-10 py-2.5 text-xs text-white placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-400/60 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-amber-300 text-xs">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-stone-400 hover:text-stone-300">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-amber-500 border-stone-600 bg-black focus:ring-amber-400">
                    <span>Ingat otorisasi pada browser ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-600 to-rose-600 hover:from-amber-600 hover:to-rose-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-amber-900/40 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-crown"></i> Otorisasi & Masuk Dashboard Owner
            </button>
        </form>

        <!-- Switcher Portals -->
        <div class="mt-6 pt-5 border-t border-stone-800 bg-black/40 -mx-6 sm:-mx-9 -mb-6 sm:-mb-9 p-4 text-center">
            <p class="text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2.5">
                Bukan Owner? Pilih Portal Login Lainnya:
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ route('login') }}" class="py-2 px-3 rounded-xl border border-rose-500/30 bg-[#12131a] hover:bg-stone-800 text-rose-300 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-rose-400"></i> Portal Pelanggan
                </a>
                <a href="{{ route('login.admin') }}" class="py-2 px-3 rounded-xl border border-indigo-500/30 bg-[#12131a] hover:bg-stone-800 text-indigo-300 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-user-shield text-indigo-400"></i> Portal Admin / Staff
                </a>
            </div>
        </div>

    </div>

    <script>
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