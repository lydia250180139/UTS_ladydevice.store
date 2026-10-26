<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - ladydevice.store</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 Icons CDN -->
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
<body class="bg-gradient-to-br from-rose-100/60 via-pink-50 to-purple-100/60 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800 selection:bg-rose-200">

    <div class="max-w-lg w-full bg-white/90 backdrop-blur-xl border border-rose-200/80 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
        
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-2 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-rose-200 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-laptop text-base"></i>
                </div>
                <div class="text-left">
                    <span class="font-serif text-2xl font-bold bg-gradient-to-r from-rose-600 to-purple-700 bg-clip-text text-transparent">ladydevice</span><span class="text-rose-500 font-extrabold text-base">.store</span>
                </div>
            </a>
            <h2 class="text-xl font-bold text-slate-900 font-serif">Daftar Akun Member</h2>
            <p class="text-xs text-slate-500 mt-1">Dapatkan kemudahan checkout, lacak pesanan, dan promo laptop</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Nabila Salsabila" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('name') border-rose-500 @enderror">
                @error('name')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-bold text-slate-700 mb-1">Alamat Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nabila@gmail.com" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('email') border-rose-500 @enderror">
                @error('email')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div>
                <label for="phone" class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="081255554444" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-bold text-slate-700 mb-1">Kata Sandi (Minimal 6 Karakter) *</label>
                <input type="password" id="password" name="password" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white @error('password') border-rose-500 @enderror">
                @error('password')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-bold text-slate-700 mb-1">Ulangi Kata Sandi *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4">
                <i class="fa-solid fa-user-plus"></i> Daftarkan Akun Saya
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-bold text-rose-600 hover:text-rose-700 hover:underline">
                Masuk di Sini
            </a>
            <span class="mx-2">&bull;</span>
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800">
                Kembali ke Toko
            </a>
        </div>

    </div>

</body>
</html>
