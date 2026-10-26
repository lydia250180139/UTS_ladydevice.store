<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // User requested explicit "Akses Terlarang" protection
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Terlarang: Anda tidak memiliki izin untuk fitur ini.'
                ], 403);
            }

            return response()->view('errors.forbidden', [
                'userRole' => $user->role,
                'requiredRoles' => $roles,
                'message' => 'Akses Terlarang: Akun Anda terdaftar sebagai "' . strtoupper($user->role) . '" dan tidak diizinkan mengakses area khusus ini.'
            ], 403);
        }

        return $next($request);
    }
}
