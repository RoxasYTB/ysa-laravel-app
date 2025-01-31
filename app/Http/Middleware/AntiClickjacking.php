<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AntiClickjacking
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Ajouter l'en-tête X-Frame-Options pour bloquer l'inclusion dans des iframes
        $response->headers->set('X-Frame-Options', 'DENY'); // OU 'SAMEORIGIN' si nécessaire

        return $response;
    }
}
