<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsPetugas
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Cek apakah user sudah login dan role_id-nya 2 (petugas PST)
        if ($user && $user->role_id == 2) {
            return $next($request);
        }

        // Jika tidak sesuai, arahkan ke halaman lain atau abort
        abort(403, 'Unauthorized action.');
    }
}
