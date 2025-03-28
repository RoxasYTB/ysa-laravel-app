<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Log de débogage pour voir si on entre dans la méthode
            DB::connection()->enableQueryLog();
            
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                // Log de connexion réussie
                DB::table('logs')->insert([
                    'level' => 'info',
                    'message' => 'Connexion réussie pour l\'utilisateur : ' . $request->email,
                    'context' => json_encode([
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return redirect()->intended('dashboard');
            }

            // Log d'échec de connexion
            DB::table('logs')->insert([
                'level' => 'warning',
                'message' => 'Échec de connexion - Identifiants incorrects',
                'context' => json_encode([
                    'attempted_email' => $request->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput($request->only('email'));

        } catch (\Exception $e) {
            // Log de l'erreur
            DB::table('logs')->insert([
                'level' => 'error',
                'message' => 'Erreur lors de la tentative de connexion: ' . $e->getMessage(),
                'context' => json_encode([
                    'attempted_email' => $request->email,
                    'ip' => $request->ip(),
                    'error' => $e->getMessage()
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->withErrors([
                'email' => 'Une erreur est survenue lors de la tentative de connexion.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 