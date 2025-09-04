<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek user sudah login dan role_id == 1 (admin)
        if (Auth::check() && Auth::user()->role_id == 1) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
