<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;      // Fix : Inscription → Inscriptions (cohérence projet)
use App\Models\Etudiants;
use App\Models\Matieres;
use App\Models\Notes;
use App\Models\Filieres;
use App\Models\SessionAcademique;  // Fix : import ajouté pour gérer la session dynamiquement
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ─────────────────────────────────────────────
    // Utilitaire : session verrouillée ?
    // ─────────────────────────────────────────────
    private function isSessionVerrouillee(): bool
    {
        $session = SessionAcademique::where('annee', '2025-2026')->first();
        return $session && $session->statut === 'verrouillee';
    }

    // ═══════════════════════════════════════
    // DASHBOARD
    // ═══════════════════════════════════════
    public function dashboard()
    {
        $user = Auth::user();

        // Fix : sessionVerrouillee dynamique au lieu de false hardcodé
        $sessionVerrouillee = $this->isSessionVerrouillee();

        // Fix : Inscription → Inscriptions
        $dossiers = Inscription::with('etudiant', 'filiere')
            ->where('statut_dossier', 'en_cours')
            ->orderBy('created_at', 'desc')
            ->get();

        $matieres = Matieres::with('filiere')->orderBy('nom_matiere')->get();
        $filieres = Filieres::all();

        $etudiants_valides = Etudiants::whereHas('inscriptions', function ($q) {
            $q->where('statut_dossier', 'valide');
        })->get();

        $total_etudiants  = Etudiants::count();
        // Fix : Inscription → Inscriptions dans les 3 compteurs
        $total_en_attente = Inscription::where('statut_dossier', 'en_cours')->count();
        $total_valides    = Inscription::where('statut_dossier', 'valide')->count();
        $total_rejetes    = Inscription::where('statut_dossier', 'rejete')->count();

        return view('admin.dashboard', compact(
            'user', 'dossiers', 'matieres', 'filieres',
            'etudiants_valides', 'total_etudiants',
            'total_en_attente', 'total_valides',
            'total_rejetes', 'sessionVerrouillee'
        ));
    }

    // ═══════════════════════════════════════
    // VALIDER UN DOSSIER
    // ═══════════════════════════════════════
    public function validerDossier($id_inscription)
    {
        // Fix : Inscription → Inscriptions
        $inscription = Inscriptions::findOrFail($id_inscription);
        $etudiant    = $inscription->etudiant;

        if (!$etudiant->matricule) {
            $etudiant->matricule = date('Y') . rand(1000, 9999);
            $etudiant->save();
        }

        $inscription->update([
            'statut_dossier'  => 'valide',
            'date_validation' => now(),
        ]);

        return redirect()->back()->with('success', 'Dossier validé et matricule attribué !');
    }

    // ═══════════════════════════════════════
    // REJETER UN DOSSIER
    // ═══════════════════════════════════════
    public function rejeterDossier(Request $request, $id_inscription)
    {
        $request->validate(['motif_rejet' => 'required|string|min:5']);

        // Fix : Inscription → Inscriptions
        Inscriptions::findOrFail($id_inscription)->update([
            'statut_dossier'  => 'rejete',
            'motif_rejet'     => $request->motif_rejet,
            'date_validation' => now(),
        ]);

        return redirect()->back()->with('error', 'Le dossier a été rejeté.');
    }

    // ═══════════════════════════════════════
    // GESTION DES MATIÈRES
    // ═══════════════════════════════════════
    public function storeMatiere(Request $request)
    {
        $validated = $request->validate([
            'nom_matiere'    => 'required|string|max:255',
            'code_matiere'   => 'nullable|string|max:50',
            'id_filiere'     => 'required|exists:filieres,id_filiere',
            'credit_matiere' => 'required|integer|min:1',
            'id_semestre'    => 'nullable|exists:semestres,id_semestre',
        ]);

        // Fix : $request->all() remplacé par $validated pour éviter la mass injection
        Matieres::create($validated);

        return redirect()->back()->with('success', 'Matière ajoutée avec succès !');
    }

    public function destroyMatiere($id)
    {
        Matieres::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Matière supprimée.');
    }

    // ═══════════════════════════════════════
    // SAISIE DES NOTES
    // ═══════════════════════════════════════
    public function storeNote(Request $request)
    {
        $request->validate([
            'id_etudiant' => 'required|exists:etudiants,id_etudiant',
            'id_matiere'  => 'required|exists:matieres,id_matiere',
            'valeur_note' => 'required|numeric|min:0|max:20',
        ]);

        Notes::updateOrCreate(
            [
                'id_etudiant' => $request->id_etudiant,
                'id_matiere'  => $request->id_matiere,
            ],
            [
                'valeur_note'   => $request->valeur_note,
                'id_enseignant' => Auth::user()->id_personnel,
                'statut'        => 'brouillon',
                'date_saisie'   => now(),
            ]
        );

        return redirect()->back()->with('success', 'La note a été enregistrée.');
    }
}
