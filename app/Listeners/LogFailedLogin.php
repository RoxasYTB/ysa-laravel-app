<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\Log;
use Illuminate\Support\Facades\Log as LaravelLog;
use App\Models\User;

class LogFailedLogin
{
    public function handle(Failed $event)
    {
        try {
            $email = $event->credentials['email'] ?? 'non fourni';
            
            // Vérifie si l'utilisateur existe
            $userExists = User::where('email', $email)->exists();
            
            $message = $userExists 
                ? "Échec de connexion : mot de passe incorrect pour l'utilisateur : {$email}"
                : "Échec de connexion : l'utilisateur n'existe pas : {$email}";

            // Création du log dans la base de données
            Log::create([
                'level' => 'warning',
                'message' => $message,
                'context' => json_encode([
                    'guard' => $event->guard ?? 'non fourni',
                    'user_exists' => $userExists,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toDateTimeString()
                ])
            ]);

            LaravelLog::info('Log d\'échec de connexion enregistré avec succès');
        } catch (\Exception $e) {
            LaravelLog::error('Erreur lors de l\'enregistrement du log d\'échec de connexion: ' . $e->getMessage());
        }
    }
} 