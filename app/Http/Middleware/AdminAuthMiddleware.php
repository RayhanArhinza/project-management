<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah guard 'admin' telah terautentikasi
        if (!Auth::guard('admin')->check()) {
            // Jika tidak terautentikasi, alihkan ke halaman login admin
            return redirect('/admin/login')->withErrors(['Anda tidak memiliki akses, silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}
