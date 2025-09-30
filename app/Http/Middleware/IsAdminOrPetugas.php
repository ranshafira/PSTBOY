<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminOrPetugas
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && ($user->hasRole('admin') || $user->hasRole('petugas'))) {
            return $next($request);
        }
        abort(403, 'Unauthorized.');
    }
}
