<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Etudiants;
use App\Models\Enseignants;
use App\Models\Inscription;
use App\Models\Semestres;
use App\Models\Matieres;
use App\Models\Filieres;
use App\Models\Departement;
use App\Models\Notes;
use App\Exports\NotesExport;
use App\Models\Deliberation;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PeriodeInscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuperAdminController extends Controller
{
    // ══════════════════════════════════════════
    // DASHBOARD
    // ══════════════════════════════════════════
    public function dashboard()
    {
        $stats = [
            'etudiants'            => Etudiants::count(),
            'enseignants'          => Enseignants::count(),
            'filieres'             => Filieres::count(),
            'inscriptions_attente' => Inscription::where('statut_dossier', 'en_attente')->count(),
            'total_users'          => User::count(),
        ];

        // Période d'inscription active
        $periode = PeriodeInscription::where('active', true)->first();
        $inscriptionOuverte = $periode && now()->between($periode->date_debut, $periode->date_fin);

        return view('superadmin.dashboard', compact('stats', 'periode', 'inscriptionOuverte'));
    }




public function toggleStatus(Request $request, $id_etudiant)
{
    // On cherche l'étudiant via sa clé primaire personnalisée id_etudiant
    $etudiant = Etudiants::where('id_etudiant', $id_etudiant)->first();

    if (!$etudiant) {
        return response()->json([
            'success' => false,
            'message' => 'Étudiant introuvable avec l\'identifiant : ' . $id_etudiant
        ], 404);
    }

    $status = $request->input('statut');

    if (in_array($status, ['actif', 'bloque'])) {
        $etudiant->statut_compte = $status;
        $etudiant->save(); // Sauvegarde en base de données

        return response()->json([
            'success' => true,
            'new_status' => $status
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Statut transmis invalide.'
    ], 400);
}

    // ══════════════════════════════════════════
    // GESTION ACADÉMIQUE
    // ══════════════════════════════════════════
    public function manageAcademics()
    {
        $departements = Departement::with('filieres.matieres')->get();

        $filieres = Filieres::with('matieres')->get();
       $departements = Departement::all();
        $matieres = Matieres::all();
        $filieres     = Filieres::with('departement')->get();
        //$filieres = Filiere::all();
        //$matieres = Matiere::all();
        $pivot = DB::table('matiere_filieres')->get();

        return view('superadmin.academics', compact('departements', 'filieres', 'matieres','pivot'));

    }

    public function storeDepartement(Request $request)
    {
        $request->validate(['nom_departement' => 'required|unique:departements']);
        Departement::create($request->all());
        return back()->with('success', 'Département ajouté.');
    }

    public function deleteDepartement($id)
    {
        Departement::destroy($id);
        return back()->with('success', 'Département supprimé.');
    }

    public function storeFiliere(Request $request)
    {
        $request->validate([
            'nom_filiere'    => 'required',
            'id_departement' => 'required|exists:departements,id_departement',
        ]);
        Filieres::create($request->all());
        return back()->with('success', 'Filière ajoutée.');
    }

    public function deleteFiliere($id)
    {
        Filieres::destroy($id);
        return back()->with('success', 'Filière supprimée.');
    }

    // ══════════════════════════════════════════
    // GESTION DES MATIÈRES
    // ══════════════════════════════════════════
public function manageMatieres(Request $request)
{
    $query = Matieres::with(['departement', 'filiere', 'semestre']); // ← singulier

    if ($request->filled('filiere_filter')) {
        $query->where('id_filiere', $request->filiere_filter);
    }

    $matieres     = $query->orderBy('nom_matiere')->get(); // ← une seule assignation
    $filieres     = Filieres::all();
    $departements = Departement::all();
    $semestres    = Semestres::all();

    return view('superadmin.matieres', compact('matieres', 'filieres', 'semestres', 'departements'));
}


public function storeMatiere(Request $request)
{
    $data = $request->validate([
        'code_matiere'   => 'required|unique:matieres,code_matiere',
        'nom_matiere'    => 'required|string|max:255',
        'credit_matiere' => 'required|integer|min:1',
        'id_filiere'     => 'required|exists:filieres,id_filiere',
        'id_semestre'    => 'nullable|exists:semestres,id_semestre',
        'id_departement' => 'nullable|exists:departements,id_departement',
    ]);

    // 1. On crée la matière (sans l'id_filiere si ta table matieres n'a plus cette colonne)
    $matiere = Matieres::create($data);

    // 2. 🔴 ON REMPLIT LA TABLE PIVOT ICI
    // Cela va insérer une ligne contenant l'id_matiere et l'id_filiere dans 'matiere_filiere'
    $matiere->filieres()->attach($request->id_filiere);

    return back()->with('success', 'Matière créée et liée à la filière avec succès.');
}

public function updateMatiere(Request $request, $id)
{
    $data = $request->validate([
        'nom_matiere'    => 'required|string|max:255',
        'code_matiere'   => 'required|unique:matieres,code_matiere,' . $id . ',id_matiere',
        'credit_matiere' => 'required|integer|min:1',
        'id_filiere'     => 'required|exists:filieres,id_filiere',
        'id_semestre'    => 'nullable|exists:semestres,id_semestre',
        'id_departement' => 'nullable|exists:departements,id_departement',
    ]);

    $matiere = Matieres::findOrFail($id);
    $matiere->update($data);

    // 3. 🔴 ON MET À JOUR LA TABLE PIVOT ICI
    // "sync" va supprimer l'ancienne liaison et mettre la nouvelle à la place
    $matiere->filieres()->sync([$request->id_filiere]);

    return back()->with('success', 'Matière mise à jour.');
}

public function deleteMatiere($id)
{
    $matiere = Matieres::findOrFail($id);

    // 4. 🔴 ON NETTOIE LA TABLE PIVOT AVANT DE SUPPRIMER
    // Évite de laisser des lignes "orphelines" dans ta table pivot
    $matiere->filieres()->detach();

    $matiere->delete();

    return back()->with('success', 'Matière supprimée.');
}
    public function exportExcel()
{
    return Excel::download(

        new NotesExport,

        'notes.xlsx'
    );
}




//public function deleteMatiere($id)
    //{
    //    $matiere = Matieres::findOrFail($id);
    //    return view('superadmin.matieres-delete', compact('matiere'));
   // }


    public function destroyMatiere($id)
    {
        $matiere = Matieres::findOrFail($id);

        // Détacher d'abord les relations existantes pour éviter les erreurs SQL de contrainte
        $matiere->filiere()->detach();
        $matiere->delete();

        return redirect()->route('superadmin.matieres')->with('success', 'Matière supprimée définitivement.');
    }

    /**
     * PAGE D'ATTRIBUTION DES MATIÈRES AUX ENSEIGNANTS
     */
    public function manageAttributions()
    {
        // Eager load des matières associées aux enseignants pour optimiser la bdd
        $enseignants = Enseignants::with('matieres')->orderBy('nom')->get();
        $matieres_liste = Matieres::orderBy('nom_matiere')->get();

        return view('superadmin.attributions', compact('enseignants', 'matieres_liste'));
    }


    public function storeAttribution(Request $request)
    {
        $request->validate([
            'id_enseignant' => 'required|exists:enseignants,id_enseignant',
            'id_matiere'    => 'required|exists:matieres,id_matiere',
        ]);

        $enseignant = Enseignants::findOrFail($request->id_enseignant);

        // syncWithoutDetaching évite les doublons si on attribue la même matière

$enseignant->matieres()->syncWithoutDetaching([$request->id_matiere]);
        return back()->with('success', 'Matière attribuée avec succès à l\'enseignant.');



    // S'il s'agit d'une modification (le modal envoie old_id_matiere)
    if ($request->has('old_id_matiere')) {
        // On détache l'ancienne matière d'abord
        $enseignant->matieres()->detach($request->old_id_matiere);
    }

    // On attache la nouvelle matière
    $enseignant->matieres()->syncWithoutDetaching([$request->id_matiere]);

    return redirect()->back()->with('success', 'Attribution mise à jour avec succès !');
    }


    /**
     * RETIRER UNE MATIÈRE À UN ENSEIGNANT
     */
    public function detachAttribution(Request $request)
    {
        $request->validate([
            'id_enseignant' => 'required|exists:enseignants,id_enseignant',
            'id_matiere'    => 'required|exists:matieres,id_matiere',
        ]);

        $enseignant = Enseignants::findOrFail($request->id_enseignant);
        $enseignant->matieres()->detach($request->id_matiere);

        return back()->with('success', 'La matière a été retirée de cet enseignant.');
    }

    // ══════════════════════════════════════════
    // GESTION DES ENSEIGNANTS
    // ══════════════════════════════════════════
  public function manageEnseignants()
{
    $enseignants  = Enseignants::with('departement')->get();
    $departements = Departement::all();

    return view('superadmin.enseignants', compact('enseignants', 'departements'));
}

    public function storeEnseignant(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:enseignants,email|unique:utilisateurs,email',
            'grade_enseignant'          => 'nullable|string|max:255',
            'id_departement' => 'nullable|exists:departements,id_departement',
        ]);

        DB::transaction(function () use ($request) {
            $enseignant = Enseignants::create([
                'nom'            => strtoupper($request->nom),
                'prenom'         => $request->prenom,
                'email'          => $request->email,
                'grade_enseignant'=> $request->grade_enseignant,
                'id_departement' => $request->id_departement,
            ]);

            //User::create([
               // 'nom'              => strtoupper($request->nom),
               // 'prenom'           => $request->prenom,
               // 'email'            => $request->email,
               // 'password'         => Hash::make('UAC-Enseignant-2026'),
                //'type_utilisateur' => 'enseignant',
               // 'id_enseignant'    => $enseignant->id_enseignant,

          //  ]);
        });

        return back()->with('success', 'Enseignant.');
    }

    public function deleteEnseignant($id)
    {
        DB::transaction(function () use ($id) {
            User::where('id_enseignant', $id)->delete();
            Enseignants::destroy($id);
        });
        return back()->with('success', 'Enseignant et son accès révoqués.');
    }

    // ══════════════════════════════════════════
    // GESTION DES NOTES
    // ══════════════════════════════════════════






public function edit($id_etudiant)
{
    $etudiant     = Etudiants::with('filiere')->findOrFail($id);
    $departements = Departement::orderBy('nom_departement')->get();

    // Charger les filières du département actuel de l'étudiant
    $filieres = collect();
    if ($etudiant->filiere) {
        $filieres = Filieres::where('id_departement', $etudiant->filiere->id_departement)
                          ->orderBy('nom_filiere')
                          ->get();
}
    return view('superadmin.etudiants.edit', compact('etudiant', 'departements', 'filieres'));
}




public function getFilieresByDepartement($id_departement)
{

    $filieres = Filieres::where('id_departement', $id_departement)->orderBy('nom_filiere', 'asc')->get();
    return response()->json($filieres);
}




public function update(Request $request, $id_etudiant)
{
    // 1. Validation stricte des données reçues (y compris le département et la filière)
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:etudiants,email,' . $id_etudiant . ',id_etudiant',
        'telephone' => 'nullable|string|max:20',
        'date_naissance' => 'nullable|date',
        'lieu_naissance' => 'nullable|string|max:255',
        'nationalite' => 'nullable|string|max:255',
        'niveau' => 'required|string|in:Licence 1,Licence 2,Licence 3,Master 1,Master 2',
        'id_filiere' => 'required|exists:filieres,id_filiere', // Vérifie que la filière existe bien en BDD
        'statut_compte' => 'required|in:actif,bloque',
    ], [
        'id_filiere.required' => 'Le choix d\'une filière est obligatoire.',
        'id_filiere.exists' => 'La filière sélectionnée est invalide.',
    ]);

    // 2. Récupération de l'étudiant
    $etudiant = Etudiants::findOrFail($id_etudiant);

    // 3. Mise à jour des champs de l'étudiant
    $etudiant->nom = $validatedData['nom'];
    $etudiant->prenom = $validatedData['prenom'];
    $etudiant->email = $validatedData['email'];
    $etudiant->telephone = $validatedData['telephone'];
    $etudiant->date_naissance = $validatedData['date_naissance'];
    $etudiant->lieu_naissance = $validatedData['lieu_naissance'];
    $etudiant->nationalite = $validatedData['nationalite'];
    $etudiant->niveau = $validatedData['niveau'];

    // Enregistrement de la nouvelle filière (le département est lié indirectement via la filière)
    $etudiant->id_filiere = $validatedData['id_filiere'];

    $etudiant->statut_compte = $validatedData['statut_compte'];

    // 4. Sauvegarde en Base de Données
    $etudiant->save();

    // 5. Redirection avec un message de succès
    return redirect()->route('superadmin.etudiants.show', $etudiant->id_etudiant)
        ->with('success', 'La fiche académique de l\'étudiant a été mise à jour avec succès.');
}




    public function getEtudiantsParMatiere(Request $request)
{
    try {
        $matiere = Matieres::with('filiere.departement')
                           ->findOrFail($request->id_matiere);

        $etudiants = Etudiants::with('filiere.departement')
            ->whereHas('inscriptions', fn($q) =>
                $q->where('statut_dossier', 'valide')
                  ->where('id_filiere', $matiere->id_filiere)
            )->orderBy('nom')->get();

        // Si aucun étudiant via inscription, fallback sur id_filiere direct
        if ($etudiants->isEmpty()) {
            $etudiants = Etudiants::with('filiere.departement')
                ->where('id_filiere', $matiere->id_filiere)
                ->orderBy('nom')->get();
        }

        $notesExistantes = Notes::where('id_matiere', $request->id_matiere)
            ->where('session', $request->session ?? 'normale')
            ->pluck('valeur_note', 'id_etudiant');

        $absents = Notes::where('id_matiere', $request->id_matiere)
            ->where('session', $request->session ?? 'normale')
            ->where('is_absent', true)
            ->pluck('id_etudiant');

        $result = $etudiants->map(fn($e) => [
            'id_etudiant'     => $e->id_etudiant,
            'nom'             => $e->nom,
            'prenom'          => $e->prenom,
            'matricule'       => $e->matricule ?? null,
            'nom_filiere'     => $e->filiere?->nom_filiere ?? null,
            'nom_departement' => $e->filiere?->departement?->nom_departement ?? null,
            'note_existante'  => $notesExistantes[$e->id_etudiant] ?? null,
            'is_absent'       => $absents->contains($e->id_etudiant),
        ]);

        return response()->json($result);

    } catch (\Exception $e) {
        return response()->json([
            'error'   => true,
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
        ], 500);
    }
}









        public function manageNotes(Request $request)
{
    $query = Notes::with([
        'etudiant.filiere.departement',  // ← relations imbriquées
        'matiere.filiere.departement',   // ← idem via matière
    ]);

    if ($request->filled('matricule')) {
        $query->whereHas('etudiant', fn($q) =>
            $q->where('matricule', $request->matricule)
        );
    }

    if ($request->filled('id_matiere')) {
        $query->where('id_matiere', $request->id_matiere);
    }

    $notes     = $query->latest()->paginate(20);
    $matieres  = Matieres::with(['filiere', 'departement'])->get();
    $etudiants = Etudiants::whereHas('inscriptions', fn($q) =>
                     $q->where('statut_dossier', 'valide')
                 )->orderBy('nom')->get();

    $dernieres_notes = Notes::with([
        'etudiant.filiere.departement',
        'matiere',
    ])->latest()->take(8)->get();

    // ← les deux lignes manquantes
    $filieres     = Filieres::all();
    $departements = Departement::all();

    return view('superadmin.notes', compact(
        'notes',
        'matieres',
        'etudiants',
        'dernieres_notes',
        'filieres',       // ← ajouté
        'departements'    // ← ajouté
    ));

 }




 public function getStatsJson() {
    $stats = [
        'etudiants' => Etudiant::count(),
        'enseignants' => Enseignant::count(),
        'filieres' => Filiere::count(),
        'inscriptions_attente' => Inscription::where('statut', 'en_attente')->count(),
        'total_users' => User::count(),
    ];
    return response()->json($stats);
}



public function toggleInscription(Request $request) {

       $statut = $request->input('ouverte'); // true ou false

       // Mettez à jour votre paramètre en BDD ici (ex: via un modèle de configuration d'options)
        //Configuration::set('inscription_ouverte', $statut);

       return response()->json([
        'success' => true,
        'ouverte' => $statut
    ]);
   }



    public function storeNote(Request $request)
{
    $request->validate([
        'id_etudiant' => 'required|exists:etudiants,id_etudiant',
        'id_matiere'  => 'required|exists:matieres,id_matiere',
        'valeur_note' => 'required|numeric|min:0|max:20',
        'session'     => 'required|in:normale,rattrapage',
    ]);

    // Récupérer l'utilisateur connecté
   $user   = Auth::user();

// id_utilisateur est la PK de ta table "utilisateurs"
$adminId =  $user->type_utilisateur === 'admin' && is_null($user->id_personnel);

Notes::updateOrCreate(
    [
        'id_etudiant' => $request->id_etudiant,
        'id_matiere'  => $request->id_matiere,
        'session'     => $request->session,
    ],
    [
        'valeur_note'  => $request->valeur_note,
        'is_published' => false,
        'statut'       => 'brouillon',
        'validated_by' => $adminId,
    ]
);


    return back()->with('success', 'Note enregistrée.');
}

    public function lockNotes($id)
    {
        // ✅ On récupère d'abord la matière concernée par l'ID
        $matiere = Matieres::findOrFail($id);

        if ($matiere->notes_locked) {
            return back()->with('error', 'Cette matière est déjà verrouillée.');
        }

        $matiere->update([
            'notes_locked' => true
        ]);

        return back()->with('success', 'Notes verrouillées.');
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'valeur_note' => 'required|numeric|min:0|max:20',
            'session'     => 'required|in:normale,rattrapage',
        ]);

        $note = Notes::findOrFail($id);

        if ($note->is_published) {
            return back()->with('error', 'Impossible de modifier une note publiée.');
        }
        $note = Notes::findOrFail($request->id_note);
    $note->valeur_note = $request->valeur_note;
    $note->save();

    // 2. Recalcul dynamique de la moyenne globale de cet étudiant
    $id_etudiant = $note->id_etudiant;
    $toutesLesNotes = Notes::where('id_etudiant', $id_etudiant)->with('matiere')->get();

    $total = 0;
    $totalCredits = 0;
    foreach($toutesLesNotes as $n) {
        $coef = $n->matiere->credit_matiere ?? 1;
        $total += $n->valeur_note * $coef;
        $totalCredits += $coef;
    }

    $nouvelleMoyenne = $totalCredits > 0 ? round($total / $totalCredits, 2) : 0;

    return response()->json([
        'success' => true,
        'nouvelle_moyenne' => number_format($nouvelleMoyenne, 2)
    ]);

        // ✅ Utilisation directe de la variable $note déjà trouvée
        $note->update([
            'valeur_note' => $request->valeur_note,
            'session'     => $request->session,
        ]);

        return back()->with('success', 'Note mise à jour.');
    }

    public function deleteNote($id)
    {
        $note = Notes::findOrFail($id);

        // ✅ On vérifie d'abord si elle est publiée AVANT de la supprimer
        if ($note->is_published) {
            return back()->with('error', 'Impossible de supprimer une note publiée.');
        }

        $note->delete();

        return back()->with('success', 'Note supprimée.');
    }


public function approveNotes($id_matiere)
{
    $user = auth()->user();

    if (!$user) {
        return back()->with('error', 'Session expirée.');
    }

    $adminId = $user->id ?? $user->id_utilisateur;

    $count = Notes::where('id_matiere', $id_matiere)
                ->where('is_published', false)
                ->update([
                    'is_published' => true,
                    'statut'       => 'valide',
                    'published_at' => now(),
                    'validated_by' => $adminId,
                    'validated_at' => now()
                ]);

    return back()->with('success', "$count note(s) publiée(s).");
}





    public function rejectNotes(Request $request, $id)
    {
        $request->validate([
            'motif' => 'required'
        ]);

        Notes::where('id_matiere', $id)
            ->update([
                'statut'       => 'rejete',
                'motif_rejet'  => $request->motif,
                'is_published' => false
            ]);

        return back()->with('success', 'Notes rejetées.');
    }






    public function unlockNotes($id)
    {
        $matiere = Matieres::findOrFail($id);

        $matiere->update([
            'notes_locked' => false
        ]);

        return back()->with('success', 'Notes déverrouillées.');
    }




public function generateDeliberations()
{
    Deliberation::truncate();

    $etudiants = Etudiants::with([
        'notes.matiere'
    ])->get();

    $classement = [];

    foreach($etudiants as $etudiant)
    {

        $notes = $etudiant->notes
                    ->where(
                        'is_published',
                        true
                    );

        if($notes->count() == 0)
        {
            continue;
        }

        $total = 0;

        $coefTotal = 0;

        $credits = 0;

        foreach($notes as $note)
        {
            $coef =
                $note->matiere->credit ?? 1;

            $total +=
                $note->valeur_note * $coef;

            $coefTotal += $coef;

            if($note->valeur_note >= 10)
            {
                $credits += $coef;
            }
        }

        $moyenne =
            $coefTotal > 0
            ? $total / $coefTotal
            : 0;

        $decision =
            $moyenne >= 10
            ? 'admis'
            : 'ajourne';

        $delib = Deliberation::create([

            'id_etudiant' =>
                $etudiant->id_etudiant,

            'moyenne_generale' =>
                round($moyenne, 2),

            'credits_valides' =>
                $credits,

            'decision' =>
                $decision
        ]);

        $classement[] = $delib;
    }

    // classement
    usort($classement,
        fn($a, $b)
        =>
        $b->moyenne_generale
        <=>
        $a->moyenne_generale
    );

    foreach($classement as $index => $d)
    {
        $d->update([
            'rang' => $index + 1
        ]);
    }

    return back()->with(
        'success',
        'Délibérations générées.'
    );
}





public function generatePdfReleves($id)
{
    $etudiant = Etudiants::with([
        'notes.matiere',
        'deliberation'
    ])->findOrFail($id);

    $pdf = Pdf::loadView(
        'pdf.releve',
        compact('etudiant')
    );

    return $pdf->download(
        'releve_'.$etudiant->nom.'.pdf'
    );
}





public function validateNotes($id_matiere)
{
    // Valide toutes les notes associées à cette matière
    Notes::where('id_matiere', $id_matiere)
        ->where('is_validated', false)
        ->update(['is_validated' => true]);

    return redirect()->back()->with('success', 'Les notes ont été validées et certifiées avec succès.');
}






public function publishNotes($id_matiere)
{
    // Publie toutes les notes validées de cette matière pour les rendre visibles aux étudiants
    Notes::where('id_matiere', $id_matiere)
        ->where('is_validated', true)
        ->where('is_published', false)
        ->update(['is_published' => true]);

    return redirect()->back()->with('success', 'Les notes ont été publiées officiellement pour les étudiants.');
}






public function storeAll(Request $request)
{
    $request->validate([
        'id_matiere' => 'required|exists:matieres,id_matiere',
        'session'    => 'required|in:normale,rattrapage',
        'notes'      => 'required|array',
    ]);

    $idMatiere = $request->id_matiere;
    $session   = $request->session;
    $notes     = $request->input('notes', []);
    $absents   = $request->input('absent', []);

    $saved   = 0;
    $skipped = 0;

    foreach ($notes as $idEtudiant => $valeur) {

        // Étudiant marqué absent → on enregistre null ou on saute selon ta logique
        if (isset($absents[$idEtudiant]) && $absents[$idEtudiant] == '1') {
            Notes::updateOrCreate(
                [
                    'id_etudiant' => $idEtudiant,
                    'id_matiere'  => $idMatiere,
                    'session'     => $session,
                ],
                [
                    'valeur_note' => null,   // null = absent
                    'is_published'=> false,
                ]
            );
            $saved++;
            continue;
        }

        // Champ laissé vide → on saute
        if ($valeur === null || $valeur === '') {
            $skipped++;
            continue;
        }

        // Validation de la valeur
        if (!is_numeric($valeur) || $valeur < 0 || $valeur > 20) {
            $skipped++;
            continue;
        }

        Notes::updateOrCreate(
            [
                'id_etudiant' => $idEtudiant,
                'id_matiere'  => $idMatiere,
                'session'     => $session,
            ],
            [
                'valeur_note' => round((float) $valeur, 2),
                'is_published'=> false,
            ]
        );
        $saved++;
    }

    return redirect()
        ->route('superadmin.notes.index')
        ->with('success', "$saved note(s) enregistrée(s), $skipped ignorée(s).");
}



//public function toggleStatus(Request $request, $id)
//{
    //$etudiant = Etudiant::findOrFail($id);

    // Sécurité de validation du statut entrant
   // $status = $request->input('statut');
   // if (in_array($status, ['actif', 'bloque'])) {
    //    $etudiant->statut_compte = $status;
    //    $etudiant->save();
//
       // return response()->json(['success' => true, 'new_status' => $status]);
   // }

   // return response()->json(['success' => false], 400);
//}







public function releveOfficiel(Request $request, $id_etudiant)
{
    $etudiant = Etudiants::with(['filiere.departement'])->findOrFail($id_etudiant);

    // Construction de la requête des notes
    $query = Notes::where('id_etudiant', $id_etudiant)->with('matiere');

    // Filtrage par semestre via AJAX si demandé
    if ($request->filled('semestre')) {
        $query->whereHas('matiere', function($q) use ($request) {
            $q->where('semestre', $request->semestre)
              ->orWhere('semestre->code_semestre', $request->semestre)
              ->orWhere('semestre->description', $request->semestre);
        });
    }

    $notes = $query->get();

    // Calculs académiques
    $totalPoints = 0;
    $totalCreditsTableau = 0;
    $creditsValides = 0;

    foreach($notes as $note) {
        $coeff = $note->matiere->credit_matiere ?? 1;
        $totalPoints += $note->valeur_note * $coeff;
        $totalCreditsTableau += $coeff;

        if($note->valeur_note >= 10) {
            $creditsValides += $coeff;
        }
    }

    $moyenneGenerale = $totalCreditsTableau > 0 ? round($totalPoints / $totalCreditsTableau, 2) : 0;

    // Attribution de la mention selon les normes UAC
    if ($moyenneGenerale >= 16) $mention = "Très Bien";
    elseif ($moyenneGenerale >= 14) $mention = "Bien";
    elseif ($moyenneGenerale >= 12) $mention = "Assez Bien";
    elseif ($moyenneGenerale >= 10) $mention = "Passable";
    else $mention = "Insuffisant";

    // Variables dynamiques transmises par AJAX
    $anneeAcademique = $request->get('annee', '2025-2026');
    $sessionActive = $request->get('session', 'Normale');
    $signataire = $request->get('signataire', 'Dr. Landry AZOGAN');

$codeDocument = 'UAC-' . strtoupper(uniqid());

$urlVerification = url('/verify-document/' . $codeDocument);

$qrCode = QrCode::format('svg')
    ->size(120)
    ->margin(1)
    ->generate($urlVerification);


    return view('superadmin.etudiants.releve', compact(
        'etudiant', 'notes', 'creditsValides', 'totalCreditsTableau',
                'moyenneGenerale', 'mention', 'anneeAcademique', 'sessionActive', 'signataire','codeDocument',
    'qrCode'
    ));

    // Si c'est une requête AJAX, on renvoie uniquement le contenu du document mis à jour
    if ($request->ajax()) {
        return response()->json([
            'html' => view('superadmin.etudiants.partials.releve-contenu', compact(
                'etudiant', 'notes', 'creditsValides', 'totalCreditsTableau',
                'moyenneGenerale', 'mention', 'anneeAcademique', 'sessionActive', 'signataire','codeDocument',
    'qrCode'
            ))->render()
        ]);
    }

      $pdf = Pdf::loadView('superadmin.etudiants.partials.releve-contenu', compact(
            'etudiant', 'notes', 'creditsValides', 'totalCreditsTableau',
                'moyenneGenerale', 'mention', 'anneeAcademique', 'sessionActive', 'signataire','codeDocument',
    'qrCode'
        ));

        return $pdf->download('Releve_' . $etudiant->matricule . '.pdf');


}



    // ══════════════════════════════════════════
    // VALIDATION DES INSCRIPTIONS
    // ══════════════════════════════════════════
   public function manageInscriptions()
{
    $inscriptions = Inscription::with(['etudiant', 'filiere'])
        ->where('statut_dossier', 'en_cours')
        ->latest()
        ->get();

    $departements = Departement::orderBy('nom_departement')->get();

    // ✅ Config des champs affichés dans la modal — ajoute ici si nouveau champ
    $modalFields = [
        'personnel' => [
            ['label' => 'Nom complet',        'key' => 'fullname'],
            ['label' => 'Email',               'key' => 'email'],
            ['label' => 'Téléphone',           'key' => 'telephone'],
            ['label' => 'Date de naissance',   'key' => 'naissance'],
            ['label' => 'Sexe',                'key' => 'sexe'],
            ['label' => 'Adresse',             'key' => 'adresse'],
            ['label' => 'Nationalité',         'key' => 'nationalite'],

            // ✅ Nouveau champ perso ? Ajoute une ligne ici
        ],
        'academique' => [
            ['label' => 'Filière',             'key' => 'filiere'],
            ['label' => 'Niveau',              'key' => 'niveau'],
            ['label' => 'Entité',              'key' => 'entite'],
            ['label' => 'Année d\'étude',      'key' => 'annee_etude'],
            ['label' => 'Régime',              'key' => 'regime'],
            ['label' => 'Statut boursier',     'key' => 'statut_boursier'],
            ['label' => 'N° Table BAC',        'key' => 'num_table_bac'],
            ['label' => 'Série BAC',           'key' => 'serie_bac'],
            ['label' => 'Année BAC',           'key' => 'annee_bac'],
            ['label' => 'Soumis le',           'key' => 'date'],
            ['label' => 'Groupe sanguin',      'key' => 'groupe_sanguin'],
            // ✅ Nouveau champ académique ? Ajoute une ligne ici
        ],
    ];

    return view('superadmin.inscriptions', compact('inscriptions', 'departements', 'modalFields'));
}



public function storeDossier(Request $request)
{
    $etudiant = auth()->user();

    // ✅ Champs texte — via fillable automatique
    $dataEtudiant = $request->only($etudiant->getFillable());

    // ✅ Gestion des fichiers séparément
    if ($request->hasFile('photo')) {
        // Supprime l'ancienne photo si elle existe
        if ($etudiant->photo) {
            \Storage::disk('public')->delete($etudiant->photo);
        }
        $dataEtudiant['photo'] = $request->file('photo')
            ->store('/photos', 'public');
    }

    if ($request->hasFile('document_cip')) {
        if ($etudiant->document_cip) {
            \Storage::disk('public')->delete($etudiant->document_cip);
        }
        $dataEtudiant['document_cip'] = $request->file('document_cip')
            ->store('dossiers/actes', 'public');
    }

    if ($request->hasFile('diplome_bac')) {
        if ($etudiant->diplome_bac) {
            \Storage::disk('public')->delete($etudiant->diplome_bac);
        }
        $dataEtudiant['diplome_bac'] = $request->file('diplome_bac')
            ->store('dossiers/diplomes', 'public');
    }

    $etudiant->update($dataEtudiant);

    Inscription::updateOrCreate(
        ['id_etudiant' => $etudiant->id],
        array_merge(
            $request->only((new Inscription)->getFillable()),
            ['statut_dossier' => 'en_cours']
        )
    );

    return response()->json(['success' => true]);
}

    public function validerInscription($id)
    {
        $inscription = Inscription::findOrFail($id);
        $etudiant    = $inscription->etudiant;

        if (!$etudiant->matricule) {
            $etudiant->matricule = date('Y') . rand(1000, 9999);
            $etudiant->save();
        }

        $inscription->update([
            'statut_dossier'  => 'valide',
            'date_validation' => now(),
             'validated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Dossier validé et matricule attribué.');
    }





   public function rejeterInscription(Request $request, $id)
{
    $request->validate([
        'motif_rejet' => 'required|string|min:5'
    ]);

    $inscription = Inscription::findOrFail($id);

    $inscription->update([
        'statut_dossier' => 'rejete',
        'motif_rejet' => $request->motif_rejet,
        'date_validation' => now(),
        'rejected_by' => auth()->id(),
    ]);

    return back()->with('error', 'Dossier rejeté.');
}


    // ══════════════════════════════════════════
    // ✅ PÉRIODE D'INSCRIPTION (bloquer/ouvrir)
    // ══════════════════════════════════════════
    public function gererPeriode(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        // Désactiver toutes les périodes existantes
        PeriodeInscription::query()->update(['active' => false]);

        // Créer la nouvelle période
        PeriodeInscription::create([
            'date_debut'   => $request->date_debut,
            'date_fin'     => $request->date_fin,
            'active'       => true,
            'annee'        => date('Y'),
        ]);

        return back()->with('success', 'Période d\'inscription définie.');
    }




    public function fermerInscriptions()
    {
        PeriodeInscription::where('active', true)
                          ->update(['date_fin' => now(), 'active' => false]);

        return back()->with('success', 'Inscriptions fermées immédiatement.');
    }




    public function inscriptionsArchive(Request $request){
    $query = Inscription::with(['etudiant', 'filiere'])
        ->whereIn('statut_dossier', ['valide', 'rejete']);

    if ($request->statut) {
        $query->where('statut_dossier', $request->statut);
    }

    if ($request->search) {
        $query->whereHas('etudiant', function ($q) use ($request) {
            $q->where('nom', 'like', "%{$request->search}%");
        });
    }

    $inscriptions = $query->latest()->get();

    return view('superadmin.inscriptions_archive', compact('inscriptions'));
}




public function archives(Request $request)
{
    $query = Inscription::with(['etudiant', 'filiere'])
                ->whereIn('statut_dossier', ['valide', 'rejete']);

    // Filtre statut
    if ($request->statut) {
        $query->where('statut_dossier', $request->statut);
    }

    // Filtre recherche par nom
    if ($request->search) {
        $query->whereHas('etudiant', function($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->search . '%');
        });
    }

    $inscriptions = $query->latest()->get();

    return view('superadmin.inscriptions-archive', compact('inscriptions'));
}





// ══════════════════════════════════════════
    // ✅ EXPORT EXCEL — Liste étudiants par filière
    // ══════════════════════════════════════════
    public function exportEtudiantsExcel($id_filiere)
    {
        $filiere   = Filieres::findOrFail($id_filiere);
        $etudiants = Etudiants::whereHas('inscription', function ($q) use ($id_filiere) {
            $q->where('id_filiere', $id_filiere)->where('statut_dossier', 'valide');
        })->get();

        // Générer CSV simple (sans dépendance externe)
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Etudiants_' . str_replace(' ', '_', $filiere->nom_filiere) . '.csv"',
        ];

        $callback = function () use ($etudiants) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            fputcsv($file, ['Matricule', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Sexe'], ';');
            foreach ($etudiants as $e) {
                fputcsv($file, [
                    $e->matricule,
                    $e->nom,
                    $e->prenom,
                    $e->email,
                    $e->telephone,
                    $e->sexe == 'M' ? 'Masculin' : 'Féminin',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }





    // ✅ EXPORT PDF — Liste étudiants par filière
    public function exportEtudiantsPdf($id_filiere)
    {
        $filiere   = Filieres::findOrFail($id_filiere);
        $etudiants = Etudiants::whereHas('inscriptions', function ($q) use ($id_filiere) {
            $q->where('id_filiere', $id_filiere)->where('statut_dossier', 'valide');
        })->get();

        $pdf = Pdf::loadView('pdf.liste_etudiants', compact('filiere', 'etudiants'));
        return $pdf->download('Liste_' . str_replace(' ', '_', $filiere->nom_filiere) . '.pdf');
    }





    // ✅ RELEVÉ DE NOTES — par étudiant
    public function telechargerReleve($id_etudiant)
    {
        $etudiant    = Etudiants::with('filiere')->findOrFail($id_etudiant);
        $inscription = Inscription::where('id_etudiant', $id_etudiant)
                                   ->where('statut_dossier', 'valide')
                                   ->with('filiere')
                                   ->first();

        if (!$inscription) {
            return back()->with('error', 'Dossier non validé.');
        }

        $notes = Notes::where('id_etudiant', $id_etudiant)
                      ->where('is_published', true)
                      ->with('matiere')
                      ->get();

        // Calcul moyenne pondérée
        $totalPoints  = 0;
        $totalCredits = 0;
        foreach ($notes as $note) {
            $coeff         = $note->matiere->credits ?? 1;
            $totalPoints  += $note->valeur_note * $coeff;
            $totalCredits += $coeff;
        }

        $moyenne = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
        $mention = match(true) {
            $moyenne >= 16 => 'Très Bien',
            $moyenne >= 14 => 'Bien',
            $moyenne >= 12 => 'Assez Bien',
            $moyenne >= 10 => 'Passable',
            default        => 'Ajourné',
        };

        $pdf = Pdf::loadView('pdf.releve_notes', compact(
            'etudiant', 'inscription', 'notes', 'totalCredits', 'moyenne', 'mention'
        ));

        return $pdf->download('Releve_' . $etudiant->matricule . '.pdf');
    }




public function indexEtudiants(Request $request)
{
    $query = Etudiants::with('filiere');

    // Filtre par filière
    if ($request->filled('filiere')) {
        $query->where('id_filiere', $request->filiere);
    }

    $etudiants = $query->get();

    $filieres = Filieres::all();

    return view(
        'superadmin.etudiants.index',
        compact('etudiants', 'filieres')
    );
}



public function updateEtudiant(Request $request, $id)
{
    $request->validate([
        'nom'         => 'required',
        'prenom'      => 'required',
        'email'       => 'required|email',
        'telephone'   => 'required',
        'niveau'      => 'required',
        'id_filiere'  => 'required',
    ]);

    $etudiant = Etudiants::findOrFail($id);

    $etudiant->update($request->all());

    return redirect()
            ->route('superadmin.etudiants.index')
            ->with('success', 'Étudiant modifié avec succès');
}

public function notesEtudiant($id)
{
    $etudiant = Etudiants::findOrFail($id);

    $notes = Notes::with('matiere')
                ->where('id_etudiant', $id)
                ->get();

    return view(
        'superadmin.etudiants.notes',
        compact('etudiant', 'notes')
    );
}

public function bloquerEtudiant($id)
{
    $etudiant = Etudiants::findOrFail($id);

    $etudiant->update([
        'statut_compte' => 'bloque'
    ]);

    return back()->with(
        'error',
        'Compte étudiant bloqué'
    );
}

public function debloquerEtudiant($id)
{
    $etudiant = Etudiants::findOrFail($id);

    $etudiant->update([
        'statut_compte' => 'actif'
    ]);

    return back()->with(
        'success',
        'Compte étudiant débloqué'
    );
}

public function editEtudiant($id)
{
    $etudiant = Etudiants::findOrFail($id);

    $filieres = Filieres::all();

    return view(
        'superadmin.etudiants.edit',
        compact('etudiant', 'filieres')
    );
}
public function showEtudiant($id)
{
    $etudiant = Etudiants::with('filiere')->findOrFail($id);

    return view('superadmin.etudiants.show', compact('etudiant'));
}

    // ══════════════════════════════════════════
    // ✅ LISTE ÉTUDIANTS PAR FILIÈRE (vue)
    // ══════════════════════════════════════════
    public function listeEtudiantsParFiliere()
    {
        $filieres = Filieres::withCount(['inscriptions' => function ($q) {
            $q->where('statut_dossier', 'valide');
        }])->get();

        return view('superadmin.liste_etudiants', compact('filieres'));
    }

    public function etudiantsFiliere($id_filiere)
    {
        $filiere   = Filieres::findOrFail($id_filiere);
        $etudiants = Etudiants::whereHas('inscriptions', function ($q) use ($id_filiere) {
            $q->where('id_filiere', $id_filiere)->where('statut_dossier', 'valide');
        })->with('notes')->get();

        return view('superadmin.etudiants_filiere', compact('filiere', 'etudiants'));
    }


public function index(Request $request)
    {
        $query = Etudiants::query()->with('filiere');

        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        if ($request->filled('statut')) {
            $query->where('statut_compte', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%")
                  ->orWhere('id_etudiant', 'LIKE', "%{$search}%");
            });
        }

        $etudiants = $query->paginate(10);

        // Si la requête est une demande AJAX, on intercepte et renvoie du JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('superadmin.etudiants.partials.table-rows', compact('etudiants'))->render(),
                'pagination' => method_exists($etudiants, 'links') ? $etudiants->appends($request->query())->links()->render() : ''
            ]);
        }

        return view('superadmin.etudiants.index', compact('etudiants'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        $filieres = Filieres::orderBy('nom_filiere', 'asc')->get();
        return view('superadmin.etudiants.create', compact('filieres'));
    }








    // Traite l'enregistrement du formulaire
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email',
            'password' => 'required|string|min:6',
            'id_filiere' => 'required|exists:filieres,id_filiere',
            'niveau' => 'required|string',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'matricule' => 'nullable|string|unique:etudiants,matricule',
            'statut_compte' => 'required|in:actif,bloque',
        ]);

        // Génération de matricule automatique si non fourni
        if (empty($validated['matricule'])) {
            $validated['matricule'] = 'MAT-' . strtoupper(substr($validated['nom'], 0, 2)) . rand(1000, 9999);
        }

        // Chiffrement du mot de passe
        $validated['password'] = Hash::make($validated['password']);

        Etudiant::create($validated);

        return redirect()->route('superadmin.etudiants.index')
                         ->with('success', 'Étudiant immatriculé avec succès.');
    }
}
