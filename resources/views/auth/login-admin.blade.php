<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin / Staff - ladydevice.store</title>
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        staff: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-4 font-sans text-slate-100 selection:bg-indigo-500 selection:text-white">

    <div class="max-w-md w-full bg-slate-800/90 backdrop-blur-xl border border-indigo-500/30 rounded-3xl p-6 sm:p-9 shadow-2xl relative overflow-hidden">
        
        <!-- Ambient Glowing Lights -->
        <div class="absolute -top-14 -right-14 w-44 h-44 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-14 -left-14 w-44 h-44 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Badge -->
        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-950 text-indigo-300 border border-indigo-700/60 shadow-sm">
                <i class="fa-solid fa-user-shield text-indigo-400"></i> Terminal Operasional & Kasir (Staff)
            </span>
        </div>

        <!-- Brand & Heading -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-2 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-cash-register text-base"></i>
                </div>
                <div class="text-left">
                    <span class="font-sans font-extrabold text-2xl tracking-tight bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">ladydevice</span><span class="text-indigo-400 font-extrabold text-base">.store</span>
                </div>
            </a>
            <h2 class="text-xl font-bold text-white tracking-tight">Portal Khusus Admin / Staff</h2>
            <p class="text-xs text-slate-400 mt-1">Sistem Point of Sale, Pembelian Stok & Inventaris Butik</p>
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

        <!-- Form Login Admin / Staff -->
        <form action="{{ route('login.admin.submit') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf

            <!-- Email (Strictly Blank) -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 mb-1.5">Alamat Email Staff / Kasir *</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="" required placeholder="staff@ladydevice.store..." 
                        autocomplete="off" 
                        class="w-full bg-slate-900/90 border border-slate-700 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('email') border-rose-500 @enderror">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password (Strictly Blank) -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5">Kata Sandi (Password) *</label>
                <div class="relative">
                    <input type="password" id="password" name="password" value="" required placeholder="Masukkan kata sandi staff..." 
                        autocomplete="new-password" 
                        class="w-full bg-slate-900/90 border border-slate-700 rounded-2xl pl-10 pr-10 py-2.5 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200 text-xs">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-400 hover:text-slate-300">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-600 bg-slate-900 focus:ring-indigo-500">
                    <span>Ingat sesi di terminal ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Terminal Staff
            </button>
        </form>

        <!-- Switcher Portals -->
        <div class="mt-6 pt-5 border-t border-slate-700/80 bg-slate-900/60 -mx-6 sm:-mx-9 -mb-6 sm:-mb-9 p-4 text-center">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                Bukan Staff? Pilih Portal Login Lainnya:
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ route('login') }}" class="py-2 px-3 rounded-xl border border-rose-500/30 bg-slate-800/80 hover:bg-slate-700 text-rose-300 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-rose-400"></i> Portal Pelanggan
                </a>
                <a href="{{ route('login.owner') }}" class="py-2 px-3 rounded-xl border border-amber-500/30 bg-slate-800/80 hover:bg-slate-700 text-amber-300 font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-crown text-amber-400"></i> Portal Owner
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