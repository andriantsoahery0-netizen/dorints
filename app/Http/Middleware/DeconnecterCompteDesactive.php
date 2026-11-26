<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Déconnecte immédiatement un utilisateur dont le compte vient d'être désactivé,
// même s'il était resté connecté ou avait coché « Se souvenir de moi »
class DeconnecterCompteDesactive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->actif === false) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Ce compte a été désactivé. Contactez un administrateur.',
                ]);
        }

        return $next($request);
    }
}
