<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Vérifie si l'utilisateur est un administrateur
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et est un administrateur
        if (!$request->user() || !$request->user()->is_admin) {
            // Rediriger vers la page d'accueil avec un message d'erreur
            return redirect('/')
                ->with('error', 'Accès refusé. Vous devez être administrateur pour accéder à cette page.');
        }

        return $next($request);
    }
}
