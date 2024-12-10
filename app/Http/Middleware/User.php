<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class User
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('siswa')->check()) {
            return redirect('/user/login');
        }

        return $next($request);
    }
}
