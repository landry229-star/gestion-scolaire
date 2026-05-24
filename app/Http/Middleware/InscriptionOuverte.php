<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PeriodeInscription;

class InscriptionOuverte
{
    public function handle(Request $request, Closure $next)
    {
        if (!PeriodeInscription::isOuverte()) {
            // Si c'est une requête AJAX/API
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Les inscriptions sont fermées.'
                ], 403);
            }

            // Sinon rediriger vers l'accueil avec message
            return redirect()->route('home')
                             ->with('error', '🔒 Les inscriptions sont actuellement fermées. Revenez pendant la période d\'inscription.');
        }

        return $next($request);
    }
}

