<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Ce contrôleur gère l'authentification des utilisateurs pour l'application
    | et les redirige vers leurs écrans spécifiques en fonction de leur rôle.
    |
    */

    use AuthenticatesUsers;

    /**
     * Où rediriger les utilisateurs après la connexion.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Créer une nouvelle instance de contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Logique de redirection après authentification réussie.
     * C'est ici que l'on gère les 4 types de profils.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->type_utilisateur === 'admin') {
        if (is_null($user->id_personnel)) {
            // C'est un admin sans lien avec le personnel -> SUPER ADMIN
            return redirect()->route('superadmin.dashboard');
        } else {
            // C'est un admin lié à un personnel -> CHEF SCOLARITÉ
            return redirect()->route('admin.dashboard');
        }
        }

        // --- 3. CAS DE L'ENSEIGNANT ---
        if ($user->type_utilisateur === 'enseignant') {
            return redirect()->route('enseignant.dashboard')
                             ->with('success', 'Espace Enseignant activé.');
        }

        // --- 4. CAS DE L'ÉTUDIANT ---
        if ($user->type_utilisateur === 'etudiant') {
            return redirect()->route('etudiant.dashboard')
                             ->with('success', 'Portail Étudiant - Session ouverte.');
        }

        // --- CAS PAR DÉFAUT ---
        // Si aucun rôle ne correspond, on déconnecte par sécurité ou on renvoie à l'accueil
        return redirect('/');
    }

    /**
     * Optionnel : Personnaliser le nom d'utilisateur (si tu utilises l'email)
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Logique de déconnexion personnalisée
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Vous avez été déconnecté avec succès.');
    }
}
