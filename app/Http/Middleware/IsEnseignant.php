<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEnseignant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->type_utilisateur === 'enseignant') {
            return $next($request);
        }

        return redirect('/')->with('error', "Accès réservé aux enseignants.");
    }
}
