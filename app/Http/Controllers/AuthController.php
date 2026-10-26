<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ==========================================
    // 1. PORTAL LOGIN KHUSUS PELANGGAN
    // ==========================================
    public function showLoginPelanggan()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login-pelanggan');
    }

    public function loginPelanggan(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Validasi kecocokan email & kata sandi
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        // 2. Ambil model user terdaftar
        $user = User::where('email', $credentials['email'])->first();

        // 3. Proteksi Akses Terlarang: Cek apakah akun adalah Pelanggan
        if (!$user || $user->role !== 'pelanggan') {
            $roleLabel = match ($user?->role) {
                'owner' => 'Owner (Pemilik Toko)',
                'staff' => 'Admin / Staff Operasional',
                default => 'Bukan Pelanggan'
            };

            $targetRoute = match ($user?->role) {
                'owner' => route('login.owner'),
                'staff' => route('login.admin'),
                default => route('login')
            };

            $targetLabel = match ($user?->role) {
                'owner' => 'Buka Portal Khusus Owner',
                'staff' => 'Buka Portal Khusus Admin / Staff',
                default => 'Kembali ke Login'
            };

            return response()->view('errors.forbidden', [
                'title' => 'Akses Terlarang: Salah Portal Login',
                'userRole' => $user?->role ?? 'unknown',
                'requiredRoles' => ['pelanggan'],
                'message' => 'Akun Anda (' . $user->email . ') terdaftar sebagai "' . $roleLabel . '". Portal ini khusus untuk Pelanggan (Customer). Anda dilarang login ke portal pelanggan menggunakan akun pengelola toko.',
                'correctPortalUrl' => $targetRoute,
                'correctPortalLabel' => $targetLabel,
            ], 403);
        }

        // 4. Lolos pengecekan, login sebagai pelanggan
        Auth::attempt($credentials, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'Selamat berbelanja di ladydevice.store, ' . $user->name . '!');
    }

    // ==========================================
    // 2. PORTAL LOGIN KHUSUS ADMIN / STAFF
    // ==========================================
    public function showLoginAdmin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Validasi kredensial
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi staff tidak sesuai.',
            ]);
        }

        // 2. Ambil user
        $user = User::where('email', $credentials['email'])->first();

        // 3. Proteksi Akses Terlarang: Khusus role 'staff'
        if (!$user || $user->role !== 'staff') {
            $roleLabel = match ($user?->role) {
                'owner' => 'Owner (Pemilik Toko)',
                'pelanggan' => 'Pelanggan / Customer',
                default => 'Bukan Staff'
            };

            $targetRoute = match ($user?->role) {
                'owner' => route('login.owner'),
                'pelanggan' => route('login'),
                default => route('login')
            };

            $targetLabel = match ($user?->role) {
                'owner' => 'Buka Portal Khusus Owner',
                'pelanggan' => 'Buka Portal Khusus Pelanggan',
                default => 'Kembali ke Login'
            };

            return response()->view('errors.forbidden', [
                'title' => 'Akses Terlarang: Bukan Akun Admin / Staff',
                'userRole' => $user?->role ?? 'unknown',
                'requiredRoles' => ['staff'],
                'message' => 'Akun Anda (' . $user->email . ') terdaftar sebagai "' . $roleLabel . '". Portal ini khusus untuk Admin / Staff Operasional. Anda dilarang login melalui terminal ini.',
                'correctPortalUrl' => $targetRoute,
                'correctPortalLabel' => $targetLabel,
            ], 403);
        }

        // 4. Lolos pengecekan, login sebagai staff
        Auth::attempt($credentials, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', 'Halo Staff ' . $user->name . ', selamat bertugas di Terminal Kasir & POS!');
    }

    // ==========================================
    // 3. PORTAL LOGIN KHUSUS OWNER
    // ==========================================
    public function showLoginOwner()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login-owner');
    }

    public function loginOwner(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Validasi kredensial
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi owner tidak sesuai.',
            ]);
        }

        // 2. Ambil user
        $user = User::where('email', $credentials['email'])->first();

        // 3. Proteksi Akses Terlarang: Khusus role 'owner'
        if (!$user || $user->role !== 'owner') {
            $roleLabel = match ($user?->role) {
                'staff' => 'Admin / Staff Operasional',
                'pelanggan' => 'Pelanggan / Customer',
                default => 'Bukan Owner'
            };

            $targetRoute = match ($user?->role) {
                'staff' => route('login.admin'),
                'pelanggan' => route('login'),
                default => route('login')
            };

            $targetLabel = match ($user?->role) {
                'staff' => 'Buka Portal Khusus Admin / Staff',
                'pelanggan' => 'Buka Portal Khusus Pelanggan',
                default => 'Kembali ke Login'
            };

            return response()->view('errors.forbidden', [
                'title' => 'Akses Terlarang: Bukan Akun Owner',
                'userRole' => $user?->role ?? 'unknown',
                'requiredRoles' => ['owner'],
                'message' => 'Akun Anda (' . $user->email . ') terdaftar sebagai "' . $roleLabel . '". Portal ini memiliki otorisasi eksekutif penuh dan hanya dapat diakses oleh Pemilik Bisnis (Owner).',
                'correctPortalUrl' => $targetRoute,
                'correctPortalLabel' => $targetLabel,
            ], 403);
        }

        // 4. Lolos pengecekan, login sebagai owner
        Auth::attempt($credentials, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, Owner ' . $user->name . '! Seluruh kendali eksekutif aktif.');
    }

    // ==========================================
    // 4. COMPATIBILITY ALIASES
    // ==========================================
    public function showLogin()
    {
        return $this->showLoginPelanggan();
    }

    public function login(Request $request)
    {
        return $this->loginPelanggan($request);
    }

    // ==========================================
    // 5. REGISTRATION & LOGOUT
    // ==========================================
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'pelanggan',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun member Anda berhasil dibuat! Selamat menjelajahi koleksi laptop estetik kami.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar dari akun.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isOwner() || $user->isStaff()) {
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('home'));
    }
}