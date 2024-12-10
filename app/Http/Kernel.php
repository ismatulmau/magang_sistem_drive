protected $routeMiddleware = [
    // Middleware lainnya
    'check.user.storage' => \App\Http\Middleware\CheckUserStorage::class,
    // Middleware lainnya
    'auth.admin' => \App\Http\Middleware\Admin::class,
    // Middleware lainnya
    'auth.siswa' => \App\Http\Middleware\EnsureSiswaIsAuthenticated::class,
];