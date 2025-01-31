<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogAuthenticationAttempts
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Si c'est une tentative de connexion (POST sur /login)
        if ($request->is('login') && $request->isMethod('post')) {
            if (!Auth::check()) {
                // L'authentification a échoué
                Log::create([
                    'level' => 'warning',
                    'message' => 'Échec de connexion pour l\'utilisateur : ' . $request->input('email'),
                    'context' => json_encode([
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'timestamp' => now()
                    ])
                ]);
            }
        }

        return $response;
    }
} 