<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Log;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        Log::create([
            'level' => 'info',
            'message' => 'Connexion réussie pour l’utilisateur : ' . $event->user->email,
        ]);
    }
} 