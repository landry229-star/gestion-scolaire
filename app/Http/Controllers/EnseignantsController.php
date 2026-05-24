<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enseignants;
use App\Models\Matieres;
use App\Models\Notes;
use App\Models\Inscription;
use App\Models\SessionAcademique;
// use App\Exports\EtudiantsMatiereExport; // Décommenter quand l'export sera créé
// use Maatwebsite\Excel\Facades\Excel;

class EnseignantsController extends Controller
{

    // ─────────────────────────────────────────────────────────────────
    // Dashboard — liste des matières de l'enseignant
    // ─────────────────────────────────────────────────────────────────
    public function index()
    {
        $enseignant = $this->getEnseignant();

        // Fix : une seule récupération avec eager loading filiere + pivot annee_academique
        $matieres = $enseignant->matieres()->with('filiere')->get();

        $sessionVerrouillee = $this->isSessionVerrouillee();

        // Fix : session dynamique au lieu de la valeur hardcodée dans la vue
        $sessionActuelle = SessionAcademique::where('statut', 'active')
            ->value('annee') ?? '—';

        return view('enseignant.index', compact(
            'enseignant',
            'matieres',
            'sessionVerrouillee',
            'sessionActuelle'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // Saisie des notes — affichage du formulaire
    // ─────────────────────────────────────────────────────────────────
    public function saisieNotes($id_matiere)
    {
        $matiere = Matieres::with('filiere')->findOrFail($id_matiere);

        $etudiants = Inscriptions::where('id_filiere', $matiere->id_filiere)
            ->with('etudiant')
            ->get();

        $sessionVerrouillee = $this->isSessionVerrouillee();

        return view('enseignant.saisie_notes', compact(
            'matiere',
            'etudiants',
            'sessionVerrouillee'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // Saisie des notes — enregistrement brouillon
    // ─────────────────────────────────────────────────────────────────
    public function storeNotes(Request $request, $id_matiere)
    {
        // Fix : vérification de sécurité EN PREMIER, avant tout traitement
        if ($this->isSessionVerrouillee() && !Auth::user()->is_superadmin) {
            abort(403, 'Tentative de modification non autorisée sur une session clôturée.');
        }

        $matiere = Matieres::findOrFail($id_matiere);

        // Fix : validation basique des notes
        $request->validate([
            'notes.*.cc'   => ['nullable', 'numeric', 'min:0', 'max:20'],
            'notes.*.exam' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $data = $request->input('notes', []);

        foreach ($data as $id_etudiant => $valeurs) {
            // Fix : variable $id_etudiant (pas $etudiant_id comme dans l'original)
            Note::updateOrCreate(
                [
                    'id_matiere'  => $id_matiere,
                    'id_etudiant' => $id_etudiant,
                ],
                [
                    'note_cc'      => $valeurs['cc']   ?? null,
                    'note_examen'  => $valeurs['exam'] ?? null,
                    'id_enseignant'=> Auth::user()->id_personnel,
                    'statut'       => 'brouillon',
                ]
            );
        }

        return back()->with('success', 'Notes enregistrées en brouillon.');
    }

    // ─────────────────────────────────────────────────────────────────
    // Utilitaire : récupère l'enseignant connecté ou abort 404
    // ─────────────────────────────────────────────────────────────────
    private function getEnseignant()
    {
        return Enseignants::where('id_enseignant', Auth::user()->id_personnel)
            ->firstOrFail();
    }

    // ─────────────────────────────────────────────────────────────────
    // Utilitaire : vérifie si la session est verrouillée
    // ─────────────────────────────────────────────────────────────────
    private function isSessionVerrouillee(): bool
    {
        $session = SessionAcademique::where('annee', '2025-2026')->first();
        return $session && $session->statut === 'verrouillee';
    }


    // ─────────────────────────────────────────────────────────────────
    // Soumission finale à l'administration
    // ─────────────────────────────────────────────────────────────────
    public function soumettreAuSuperAdmin($id_matiere)
    {
        // Fix : même vérification de sécurité que pour store
        if ($this->isSessionVerrouillee() && !Auth::user()->is_superadmin) {
            abort(403, 'Soumission impossible : session clôturée.');
        }

        Note::where('id_matiere', $id_matiere)
            ->where('id_enseignant', Auth::user()->id_personnel)
            ->update(['statut' => 'soumis']);

        return redirect()->route('enseignant.index')
            ->with('success', 'Notes soumises pour validation.');
    }

    // ─────────────────────────────────────────────────────────────────
    // Liste des étudiants d'une matière (consultation)
    // ─────────────────────────────────────────────────────────────────
    public function listeEtudiants($id_matiere)
    {
        $matiere = Matieres::with('filiere')->findOrFail($id_matiere);

        $etudiants = Inscriptions::where('id_filiere', $matiere->id_filiere)
            ->with('etudiant')
            ->get();

        return view('enseignant.liste_etudiants', compact('matiere', 'etudiants'));
    }

    // ─────────────────────────────────────────────────────────────────
    // Export Excel — liste d'une matière
    // ─────────────────────────────────────────────────────────────────
    public function exportEtudiants($id_matiere)
    {
        // Décommenter quand EtudiantsMatiereExport sera créé :
        // return Excel::download(new EtudiantsMatiereExport($id_matiere), 'liste_etudiants.xlsx');

        return back()->with('info', 'Export non encore disponible.');
    }

    // ─────────────────────────────────────────────────────────────────
    // Export Excel — toutes les matières de l'enseignant
    // ─────────────────────────────────────────────────────────────────
    public function exportAll()
    {
        // Décommenter quand l'export global sera créé :
        // $enseignant = $this->getEnseignant();
        // return Excel::download(new EnseignantExportAll($enseignant), 'listes_appel.xlsx');

        return back()->with('info', 'Export global non encore disponible.');
    }
}
