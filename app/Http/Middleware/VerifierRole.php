<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifierRole
{
    /**
     * Vérifie le rôle avant de laisser passer la requête.
     *
     * @param  string ...$rolesAutorises  Les rôles autorisés (ex: 'artisan', 'admin')
     */
    public function handle(Request $request, Closure $suite, string ...$rolesAutorises): Response
    {
        // Cas 1 : L'utilisateur n'est pas connecté
        // → On le redirige vers la page de connexion
        if (! $request->user()) {
            return redirect()->route('connexion');
        }

        // Cas 2 : Le compte est désactivé
        // → On le déconnecte et on affiche un message d'erreur
        if (! $request->user()->est_actif) {
            Auth::logout();
            return redirect()
                ->route('connexion')
                ->withErrors(['email' => 'Votre compte a été désactivé.']);
        }

        // Cas 3 : L'utilisateur n'a pas le bon rôle
        // → Erreur 403 (accès interdit)
        if (! in_array($request->user()->role, $rolesAutorises)) {
            abort(403, 'Vous n\'avez pas accès à cette page.');
        }

        // Tout est bon → on laisse passer la requête
        return $suite($request);
    }
}
