<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Default: 'self'
        $cspPolicy = "default-src 'self'; ";
        
        // SCRIPT-SRC: Izinkan 'self', unsafe-eval, unsafe-inline, dan semua CDN yang Anda pakai
        $cspPolicy .= "script-src 'self' 'unsafe-eval' 'unsafe-inline' https://www.google-analytics.com https://cdn.tailwindcss.com https://unpkg.com https://cdn.jsdelivr.net; ";
        
        // STYLE-SRC: Izinkan 'self', unsafe-inline, Google Fonts, dan CDN yang dipakai
        $cspPolicy .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; "; 
        
        // FONT-SRC: Izinkan 'self', Google Fonts, dan format DATA URI
        $cspPolicy .= "font-src 'self' https://fonts.gstatic.com data:; ";
        
        // CONNECT-SRC: Izinkan 'self' dan CDN yang dipakai (untuk source maps, AJAX, dll.)
        $cspPolicy .= "connect-src 'self' https://cdn.jsdelivr.net https://www.google-analytics.com; "; 
        
        // IMG-SRC
        $cspPolicy .= "img-src 'self' data: https://www.google-analytics.com https://*.googleusercontent.com; ";
        
        // FRAME-ANCESTORS (Pencegahan Clickjacking)
        $cspPolicy .= "frame-ancestors 'self'; ";

        // Terapkan Header
        $response->headers->set('Content-Security-Policy', $cspPolicy);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        return $response;
    }
}
