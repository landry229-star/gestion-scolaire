<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etudiants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InscriptionController extends Controller
{



public function showForm()
    {
        // Récupérer les filières pour le select de l'étape 2
        $filieres = Filieres::all();
        $etudiant = auth()->user()->etudiant ?? new Etudiants();
        $inscription = $etudiant ? Inscription::where('id_etudiant', $etudiant->id_etudiant)->latest()->first() : null;

        return view('etudiant.dossier', compact('filieres', 'etudiant', 'inscription'));
    }

    public function inscrire(Request $request)
    {
        // 1. Validation de la création de compte
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email', // Adapté à ta table users
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Récupérer l'étudiant lié à cet email
        $etudiant = Etudiants::where('email', $request->email)->first();

        // 3. Créer l'utilisateur
        $utilisateur = User::create([
            'nom'              => $request->nom,
            'prenom'           => $request->prenom,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'type_utilisateur' => 'etudiant',
            'id_etudiant'      => $etudiant?->id_etudiant ?? null,
        ]);

        auth()->login($utilisateur);

        return redirect()->route('dashboard')->with('success', 'Inscription réussie !');
    }

    /**
     * Traitement de la soumission du formulaire par étapes (AJAX)
     */
    public function stockerInscription(Request $request)
    {
        // 1. Récupération de l'étudiant connecté
        $etudiant = auth()->user()->etudiant;
        if (!$etudiant) {
            return response()->json(['success' => false, 'message' => 'Profil étudiant introuvable.'], 404);
        }

        // 2. Validation stricte des données issues des 3 étapes du formulaire
        $request->validate([
            'sexe'             => 'required|in:M,F',
            'date_naissance'   => 'required|date',
            'lieu_naissance'   => 'required|string|max:255',
            'telephone'        => 'required|string',
            'nationalite'      => 'required|string',
            'adresse'          => 'required|string',
            'id_filiere'       => 'required|exists:filieres,id_filiere',
            'regime'           => 'required|in:cours_du_jour,cours_du_soir',
            'statut_boursier'  => 'required|string',
            'num_table_bac'    => 'required|string',
            'serie_bac'        => 'required|string',
            'annee_bac'        => 'required|integer',
            'photo'            => 'nullable|image|max:2048',
            'document_cip'     => 'nullable|file|mimes:pdf,jpeg,png|max:4096',
            'diplome_bac'      => 'nullable|file|mimes:pdf,jpeg,png|max:4096',
        ]);

        $filiere = Filieres::findOrFail($request->id_filiere);

        // 3. Traitement et stockage des fichiers si présents
        if ($request->hasFile('photo')) {
            $etudiant->photo = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('document_cip')) {
            $etudiant->document_cip = $request->file('document_cip')->store('cip', 'public');
        }
        if ($request->hasFile('diplome_bac')) {
            $etudiant->diplome_bac = $request->file('diplome_bac')->store('bac', 'public');
        }

        // Mise à jour des infos sur la table de l'étudiant
        $etudiant->update([
            'sexe'           => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
        ]);

        // 4. Calcul automatique des frais d'inscription du rectorat
        $montantFrais = ($request->statut_boursier === 'Boursier') ? 0.00 : 15000.00;

        // 5. Enregistrement / Historisation dans la table Inscriptions (selon ton fillable)
        $inscription = Inscription::create([
            'id_etudiant'      => $etudiant->id_etudiant,
            'id_filiere'       => $filiere->id_filiere,
            'telephone'        => $request->telephone,
            'adresse'          => $request->adresse,
            'groupe_sanguin'   => $request->groupe_sanguin,
            'annee_academique' => date('Y') . '-' . (date('Y') + 1), // Dynamique : ex 2026-2027
            'num_table_bac'    => $request->num_table_bac,
            'serie_bac'        => $request->serie_bac,
            'annee_bac'        => $request->annee_bac,
            'entite'           => $filiere->entite_uac ?? 'Faculté des Sciences et Techniques (FAST)',
            'annee_etude'      => $filiere->code ?? 'LRs1s2-MIA',
            'regime'           => $request->regime,
            'statut_boursier'  => $request->statut_boursier,
            'montant_frais'    => $montantFrais,
            'restant_du'       => $montantFrais,
            'statut_dossier'   => 'EN_COURS'
        ]);

        // 6. Formatage et réponse JSON attendue par le script AJAX
        $codeTechnique = "1#" . $etudiant->id_etudiant . "#" . date('y') . "#" . ($filiere->code ?? 'BAC');

        return response()->json([
            'success'        => true,
            'message'        => 'Préinscription enregistrée avec succès.',
            'code_technique' => $codeTechnique,
            'date_retrait'   => now()->translatedFormat('d F Y à H:i:s'),
            'etudiant'       => [
                'identifiant' => strtolower($etudiant->prenom) . '.' . $etudiant->id_etudiant,
                'matricule'   => $etudiant->matricule ?? 'En attente',
            ],
            'inscription'    => [
                'entite'     => $inscription->entite,
                'code_etape' => $inscription->annee_etude,
                'formation'  => $filiere->nom_filiere,
                'statut'     => $inscription->statut_boursier,
                'montant'    => number_format($inscription->montant_frais, 0, '.', ' '),
                'restant_du' => number_format($inscription->restant_du, 0, '.', ' '),
            ]
        ]);
    }

    public function validerInscription($id)
    {
        $inscription = Inscription::findOrFail($id);
        $anneeEnCours = date('Y');

        $dernierNumero = Inscription::whereYear('date_validation', $anneeEnCours)
                                    ->whereNotNull('numero_attestation')
                                    ->orderBy('numero_attestation', 'desc')
                                    ->first();

        if ($dernierNumero) {
            $prochainNumero = str_pad((int)$dernierNumero->numero_attestation + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $prochainNumero = '0001';
        }

        $inscription->update([
            'statut_dossier'     => 'valide',
            'numero_attestation' => $prochainNumero,
            'date_validation'    => now(),
            'montant_frais'      => $inscription->montant_frais ?? 15000.00,
            'restant_du'         => $inscription->restant_du ?? 0.00,
        ]);

        return redirect()->back()->with('success', 'Inscription officiellement validée ! Attestation N° ' . $prochainNumero . ' générée.');
    }















 








}
