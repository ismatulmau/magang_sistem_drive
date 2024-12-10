protected $routeMiddleware = [
    // Middleware lainnya...
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
];
