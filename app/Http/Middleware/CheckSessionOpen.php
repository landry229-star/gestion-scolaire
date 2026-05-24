<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // On récupère la session active
    $session = SessionAcademique::where('annee', '2025-2026')->first();

    // Condition : Si la session est verrouillée ET que l'utilisateur n'est PAS Super-Admin
    if ($session && $session->statut === 'verrouillee' && !auth()->user()->is_superadmin) {

        // Bloquer la requête
        return redirect()->route('dashboard')->with('error', 'La session académique est clôturée. Les modifications sont désormais réservées au Super-Administrateur.');
    }

    return $next($request);
}
}
