<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // On vérifie qu'il est admin ET qu'il n'est pas un personnel (Chef Scolarité)
    if (auth()->check() && auth()->user()->type_utilisateur === 'admin' && is_null(auth()->user()->id_personnel)) {
        return $next($request);
    }

    return redirect('/')->with('error', "Accès réservé à l'administrateur système.");
}
}
