<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantOnly
{
   // public function handle(Request $request, Closure $next)
   // {
    //    $user = Auth::user();

        // Non connecté → login
     //   if (!$user) {
     //      return redirect()->route('login');
      // }

        // Connecté mais pas étudiant → redirection selon rôle
         // if ($user->type_utilisateur !== 'etudiant') {
             //return match($user->type_utilisateur) {
           //      'admin', 'chef_scolarite' => redirect()->route('admin.dashboard'),
            //     'enseignants'              => redirect()->route('enseignant.dashboard'),
             //    default                   => redirect()->route('home')
               //                                         ->with('error', 'Accès non autorisé.'),
            // };
        // }

        // L'utilisateur doit avoir un id_etudiant
       // if (!$user->id_etudiant) {
         //   Auth::logout();
         //   return redirect()->route('login')
            //                 ->with('error', 'Compte étudiant incomplet. Contactez l\'administration.');
      //  }
//
   //  return $next($request);
   // }
}
