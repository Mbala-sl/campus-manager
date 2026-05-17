<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Empêche le navigateur de mettre en cache les pages protégées.
 * Ainsi, après déconnexion, le bouton « Retour » ne peut pas afficher
 * une page d'administration depuis le cache.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        return $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, private')
            ->header('Pragma',        'no-cache')
            ->header('Expires',       '0');
    }
}
