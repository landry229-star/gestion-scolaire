<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\User;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthEtudiantController extends Controller
{


// ---------------------------------------------------------------
    // OPTION A : INSCRIPTION NOUVEAU BACHELIER
    // ---------------------------------------------------------------
    public function showRegister()
    {
        return view('auth.register_etudiant');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|email|unique:utilisateurs,email|unique:etudiants,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // 1. Créer l'étudiant
        $etudiant = Etudiants::create([
            'nom'    => strtoupper($request->nom),
            'prenom' => $request->prenom,
            'email'  => $request->email,
        ]);

        // 2. Créer son compte utilisateur lié
        User::create([
            'nom'              => strtoupper($request->nom),
            'prenom'           => $request->prenom,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'type_utilisateur' => 'etudiant',
            'id_etudiant'      => $etudiant->id_etudiant,
        ]);

        // 3. Créer une ligne d'inscription en attente
        Inscription::create([
            'id_etudiant'    => $etudiant->id_etudiant,
            'statut_dossier' => 'en_attente',
        ]);

        return redirect()->route('login')
                         ->with('success', 'Votre compte a été créé ! Connectez-vous pour continuer.');
    }

    // ---------------------------------------------------------------
    // OPTION B : ACTIVATION COMPTE RÉPERTORIÉ
    // ---------------------------------------------------------------
    public function showActivationForm()
    {
        return view('auth.activation');
    }

    public function processActivation(Request $request)
    {
        $request->validate([
            'matricule' => 'required',
            'email'     => 'required|email|unique:utilisateurs,email',
            'password'  => 'required|min:6|confirmed',
        ]);

        // 1. Chercher l'étudiant par matricule et email
        $etudiant = Etudiants::where('matricule', $request->matricule)
                             ->where('email', $request->email)
                             ->first();

        if (!$etudiant) {
            return back()->withErrors([
                'erreur' => 'Les informations ne correspondent à aucun dossier officiel.'
            ]);
        }

        // 2. Vérifier si un compte existe déjà
        if (User::where('id_etudiant', $etudiant->id_etudiant)->exists()) {
            return back()->withErrors([
                'erreur' => 'Ce compte est déjà activé. Veuillez vous connecter.'
            ]);
        }

        // 3. Créer le compte utilisateur
        User::create([
            'nom'              => strtoupper($etudiant->nom),
            'prenom'           => $etudiant->prenom,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'type_utilisateur' => 'etudiant',
            'id_etudiant'      => $etudiant->id_etudiant,
        ]);

        return redirect()->route('login')
                         ->with('success', 'Votre compte est activé avec succès ! Connectez-vous.');
    }



    // ---------------------------------------------------------------
    // CONNEXION
    // ---------------------------------------------------------------
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user(); // ✅ $user défini ICI après Auth::attempt

            // ✅ Redirection selon le rôle
            return match(true) {
                // Super Admin : type 'admin' + pas de personnel lié
                $user->type_utilisateur === 'admin' && is_null($user->id_personnel)
                    => redirect()->route('superadmin.dashboard'),

                // Chef Scolarité : type 'admin' + personnel lié
                $user->type_utilisateur === 'admin' && !is_null($user->id_personnel)
                    => redirect()->route('admin.dashboard'),

                // Chef Scolarité (type dédié)
                $user->type_utilisateur === 'chef_scolarite'
                    => redirect()->route('admin.dashboard'),

                // Enseignant
                $user->type_utilisateur === 'enseignant'
                    => redirect()->route('enseignant.dashboard'),

                // Étudiant
                $user->type_utilisateur === 'etudiant'
                    => redirect()->route('etudiant.dashboard'),

                // Rôle inconnu → sécurité
                default => redirect()->route('home')
                                     ->with('error', 'Rôle non reconnu.'),
            };
        }

        return back()
            ->withErrors(['email' => 'Email ou mot de passe incorrect.'])
            ->onlyInput('email');
    }



    // ---------------------------------------------------------------
    // DÉCONNEXION
    // ---------------------------------------------------------------
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
