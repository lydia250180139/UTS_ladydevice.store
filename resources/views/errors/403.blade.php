@include('errors.forbidden', [
    'userRole' => auth()->user()->role ?? 'Guest',
    'requiredRoles' => ['Khusus Otorisasi'],
    'message' => 'Akses Terlarang: Anda tidak memiliki izin untuk halaman ini.'
])
