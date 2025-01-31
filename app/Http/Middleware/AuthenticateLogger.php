<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthenticateLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $context = [
            'guard' => 'web',
            'user_exists' => true,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        if ($response->getStatusCode() === 401) {
            // Log d'échec de connexion avec ajustement de l'heure
            $context['created_at'] = Carbon::now()->subHour();
            Log::channel('daily')->withContext($context)->info(
                "Échec de connexion : l'utilisateur n'existe pas : " . $request->email,
                $context
            );
        } elseif ($response->getStatusCode() === 302 && $request->is('login')) {
            // Log de connexion réussie sans ajustement
            Log::channel('daily')->withContext($context)->info(
                "Connexion réussie pour l'utilisateur : " . $request->email,
                $context
            );
        }

        return $response;
    }
} 