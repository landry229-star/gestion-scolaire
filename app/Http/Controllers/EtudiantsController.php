<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Filieres;
use App\Models\Etudiants;
use App\Models\Inscription;
use App\Models\Notes;
use App\Models\Examen;
use App\Models\SessionAcademique;
use  \App\Models\PeriodeInscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EtudiantsController extends Controller
{
    // ---------------------------------------------------------------
    // HELPER : retourne le vrai objet Etudiants sécurisé
    // ---------------------------------------------------------------
    private function getEtudiantSecurise(): Etudiants
    {
        $user = Auth::user();

        if ($user->type_utilisateur !== 'etudiant') {
            abort(403, 'Accès réservé aux étudiants.');
        }

        if (!$user->id_etudiant) {
            abort(403, 'Aucun profil étudiant associé à ce compte.');
        }

        $etudiant = Etudiants::with(['notes.matiere', 'filiere', 'deliberation'])
                             ->find($user->id_etudiant);

        if (!$etudiant) {
            abort(404, 'Profil étudiant introuvable.');
        }

        return $etudiant;
    }

    // ---------------------------------------------------------------
    // DASHBOARD
    // ---------------------------------------------------------------
    public function index()
    {
        $etudiant = $this->getEtudiantSecurise();
        $filieres = Filieres::orderBy('nom_filiere')->get();


        // On utilise ta méthode statique pour savoir si la campagne est valide et ouverte
$inscriptionOuverte = PeriodeInscription::isOuverte();

// On récupère la période active pour connaître l'année académique en cours (ex: 2026-2027)
$sessionActuelle =PeriodeInscription::where('active', true)->first();

$inscription = Inscription::with('filiere')
                          ->where('id_etudiant', $etudiant->id_etudiant)
                          ->latest()
                          ->first();
        // On va chercher la session en cours
      //  $sessionActuelle = SessionAcademique::latest()->first();
       // $inscriptionOuverte = $sessionActuelle ? ($sessionActuelle->statut === 'ouverte') : false;


        if (!$inscription) {
            $inscription = new Inscription(['statut_dossier' => 'non_soumis']);
        }

        // Notes publiées uniquement
        $notes   = $etudiant->notes->where('is_published', true);
        $moyenne = $notes->count() > 0 ? round($notes->avg('valeur_note'), 2) : null;

        // Notes en cours de saisie (non publiées)
        $noteEnCours = false;
        if ($inscription->statut_dossier === 'valide') {
            $noteEnCours = Notes::where('id_etudiant', $etudiant->id_etudiant)
                                ->where('is_published', false)
                               ->exists();
        }

        // Délibération
        $deliberation = $etudiant->deliberation;

        // Prochains examens (3 max)
        $prochainExamens = [];
        if ($inscription->statut_dossier === 'valide' && $etudiant->id_filiere) {
            $prochainExamens = Examen::where('id_filiere', $etudiant->id_filiere)
                                     ->where('date_examen', '>=', now())
                                     ->orderBy('date_examen')
                                     ->limit(3)
                                     ->get();
        }

        return view('etudiant.dashboard', compact(
            'etudiant',
            'filieres',
            'inscription',
            'notes',
            'moyenne',
            'noteEnCours',
            'deliberation',
            'prochainExamens',
            'inscriptionOuverte'
        ));
    }

    // ---------------------------------------------------------------
    // ENREGISTRER / CORRIGER LE DOSSIER (STYLE UAC VIA AJAX)
    // ---------------------------------------------------------------
    public function storeProfile(Request $request)
    {
        $etudiant = $this->getEtudiantSecurise();

// SÉCURITÉ : Appel direct à ta fonction du modèle
if (!\App\Models\PeriodeInscription::isOuverte()) {
    return response()->json([
        'success' => false,
        'message' => 'Action interdite. Les inscriptions sont fermées ou la période est expirée.'
    ], 403);
}

$sessionActuelle = PeriodeInscription::where('active', true)->first();
$anneeAca = $sessionActuelle ? $sessionActuelle->annee : date('Y') . '-' . (date('Y') + 1);

$inscriptionExistante = Inscription::where('id_etudiant', $etudiant->id_etudiant)
                                  ->where('annee_academique', $anneeAca)
                                  ->first();

// SÉCURITÉ : Si le dossier est validé, interdiction de modifier
if ($inscriptionExistante && $inscriptionExistante->statut_dossier === 'valide') {
    return response()->json([
        'success' => false,
        'message' => 'Votre dossier a déjà été validé par la scolarité.'
    ], 403);
}


        // Règles de validation dynamiques et robustes adaptées au formulaire Blade par étapes
        $rules = [
            'sexe'               => 'required|in:M,F',
            'date_naissance'     => 'required|date|before:today',
            'lieu_naissance'     => 'required|string|max:150',
            'nationalite'        => 'required|string|max:100',
            'adresse'            => 'required|string|max:255',
            'telephone'          => 'required|string|max:50',
            'id_filiere'         => 'required|exists:filieres,id_filiere',
            'regime'             => 'required|in:cours_du_jour,cours_du_soir',
            'statut_boursier'    => 'required|in:Non Boursier,Boursier,Exonéré',
            'num_table_bac'    => 'required|string|max:100',
            'serie_bac'          => 'required|string|max:10',
            'annee_bac'          => 'required|integer|min:2010|max:' . date('Y'),
            'groupe_sanguin'     => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'reference_paiement' => 'nullable|string|max:100', // Optionnel au dépôt initial selon l'UAC
        ];

        // Validation stricte des fichiers requis uniquement au premier upload
        $rules['document_cip'] = (!$etudiant->document_cip) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:4096' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096';
        $rules['diplome_bac']  = (!$etudiant->diplome_bac)  ? 'required|file|mimes:pdf,jpg,jpeg,png|max:4096' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096';
        $rules['photo']        = (!$etudiant->photo)        ? 'required|image|mimes:jpg,jpeg,png|max:2048'  : 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        // Si la requête est en AJAX (comme ton formulaire), on peut intercepter la validation
        if ($request->expectsJson()) {
            $request->validate($rules);
        } else {
            $request->validate($rules);
        }

        // 1. Préparation et mise à jour des données de base de l'étudiant
        $dataEtudiant = [
            'sexe'           => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'nationalite'    => $request->nationalite,
            'adresse'        => $request->adresse,
            'telephone'      => $request->telephone,

        ];

        // Sauvegarde intelligente des fichiers (écrase l'ancien si un nouveau est fourni)
        if ($request->hasFile('photo')) {
            if ($etudiant->photo) { Storage::disk('public')->delete($etudiant->photo); }
            $dataEtudiant['photo'] = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('document_cip')) {
            if ($etudiant->document_cip) { Storage::disk('public')->delete($etudiant->document_cip); }
            $dataEtudiant['document_cip'] = $request->file('document_cip')->store('dossiers/actes', 'public');
        }
        if ($request->hasFile('diplome_bac')) {
            if ($etudiant->diplome_bac) { Storage::disk('public')->delete($etudiant->diplome_bac); }
            $dataEtudiant['diplome_bac'] = $request->file('diplome_bac')->store('dossiers/diplomes', 'public');
        }

        $etudiant->update($dataEtudiant);

        // 2. Détermination dynamique des frais
        $montantFrais = ($request->statut_boursier === 'Boursier') ? 0.00 : 15000.00;
        $filiere = Filieres::find($request->id_filiere);

        // Création ou Mise à jour de la demande d'inscription
$inscription = Inscription::updateOrCreate(
    [
        'id_etudiant'      => $etudiant->id_etudiant,
        'annee_academique' => $anneeAca // Utilise l'année dynamique de ta période active
    ],
    [
                'id_filiere'         => $request->id_filiere,
                'telephone'          => $request->telephone,
                'adresse'            => $request->adresse,
                'groupe_sanguin'     => $request->groupe_sanguin,
                'num_table_bac'      => $request->num_table_bac,
                'serie_bac'          => $request->serie_bac,
                'annee_bac'          => $request->annee_bac,
                'entite'             => $filiere->entite_uac ?? 'Faculté des Sciences et Techniques (FAST)',
                'annee_etude'        => $filiere->code ?? 'L1',
                'regime'             => $request->regime,
                'statut_boursier'    => $request->statut_boursier,
                'reference_paiement' => $request->reference_paiement,
                'montant_frais'      => $montantFrais,
                'restant_du'         => $montantFrais,
                'statut_dossier'     => 'en_cours', // Repasse en examen scolarité
                'motif_rejet'        => null,
            ]
        );

        // Si la requête provient du script JS de ton formulaire (Fetch / AJAX)
        if ($request->wantsJson() || $request->ajax()) {
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

        return redirect()->route('etudiant.dashboard')
                         ->with('success', 'Votre dossier d’inscription a été soumis avec succès !');
    }

    // ---------------------------------------------------------------
    // PAGE NOTES DÉTAILLÉE
    // ---------------------------------------------------------------
    public function notes()
    {
        $etudiant = $this->getEtudiantSecurise();

        $inscription = Inscription::where('id_etudiant', $etudiant->id_etudiant)
                                  ->where('statut_dossier', 'valide')
                                  ->first();

        if (!$inscription) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Vos notes seront disponibles après validation de votre dossier.');
        }

        $notes = Notes::where('id_etudiant', $etudiant->id_etudiant)
                      ->where('is_published', true)
                      ->with('matiere')
                      ->get();

        $moyenne = $notes->count() > 0 ? round($notes->avg('valeur_note'), 2) : null;

        $stats = [
            'total'    => $notes->count(),
            'admis'    => $notes->where('valeur_note', '>=', 10)->count(),
            'max'      => $notes->max('valeur_note'),
            'min'      => $notes->min('valeur_note'),
        ];

        $noteEnCours = Notes::where('id_etudiant', $etudiant->id_etudiant)
                            ->where('is_published', false)
                            ->exists();

        return view('etudiant.notes', compact('etudiant', 'notes', 'moyenne', 'stats', 'noteEnCours', 'inscription'));
    }

    // ---------------------------------------------------------------
    // PAGE EXAMENS
    // ---------------------------------------------------------------
    public function examens()
    {
        $etudiant = $this->getEtudiantSecurise();

        $inscription = Inscription::where('id_etudiant', $etudiant->id_etudiant)
                                  ->where('statut_dossier', 'valide')
                                  ->first();

        if (!$inscription) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Les examens seront disponibles après validation de votre dossier.');
        }

        if (!$etudiant->id_filiere) {
            $examens  = collect();
            $resultats = collect();
            return view('etudiant.examens', compact('etudiant', 'examens', 'resultats', 'inscription'));
        }

        $examens = Examen::where('id_filiere', $etudiant->id_filiere)
                         ->orderBy('date_examen')
                         ->get()
                         ->groupBy(fn($e) => \Carbon\Carbon::parse($e->date_examen)->format('Y-m'));

        $resultats = $etudiant->resultatsExamens()
                              ->with('examen')
                              ->get()
                              ->keyBy('id_examen');

        return view('etudiant.examens', compact('etudiant', 'examens', 'resultats', 'inscription'));
    }

    // ---------------------------------------------------------------
    // PAGE DOSSIER (Formulaire d'édition)
    // ---------------------------------------------------------------
    public function dossier()
    {
        $etudiant = $this->getEtudiantSecurise();
        $filieres = Filieres::orderBy('nom_filiere')->get();

        $inscription = Inscription::with('filiere')
                                  ->where('id_etudiant', $etudiant->id_etudiant)
                                  ->latest()
                                  ->first();

        return view('etudiant.dossier', compact('etudiant', 'filieres', 'inscription'));
    }

    // ---------------------------------------------------------------
    // PARAMÈTRES
    // ---------------------------------------------------------------
    public function parametres()
    {
        $etudiant = $this->getEtudiantSecurise();
        return view('etudiant.parametres', compact('etudiant'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'password.min'              => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('etudiant.parametres')
                         ->with('success', 'Mot de passe mis à jour avec succès.');
    }

    // ---------------------------------------------------------------
    // 1. FICHE DE PRÉ-INSCRIPTION PDF (PROVISOIRE — DOSSIER EN ATTENTE)
    // ---------------------------------------------------------------


public function genererPdfPreInscription()
{
    $etudiant = $this->getEtudiantSecurise();

    $inscription = Inscription::with('filiere')
                               ->where('id_etudiant', $etudiant->id_etudiant)
                               ->latest()
                               ->first();

    if (!$inscription || $inscription->statut_dossier === 'non_soumis') {
        return redirect()->route('etudiant.dashboard')
                         ->with('error', 'Veuillez d\'abord soumettre votre dossier.');
    }

    // 1. Définir le contenu du QR Code (Exemple : Une chaîne textuelle sécurisée ou une URL de vérification)
    $qrData = "Matricule: " . $etudiant->matricule . "\n" .
              "Nom: " . $etudiant->nom_complet . "\n" .
              "Filière: " . ($inscription->filiere?->nom_filiere ?? 'N/A') . "\n" .
              "Statut: " . $inscription->statut_dossier;

   // 2. Génération en SVG (natif, sans dépendance Imagick) mais encodé en Base64 pour DomPDF
$qrCodeRaw = QrCode::format('svg')
                    ->size(100)
                    ->margin(0)
                    ->backgroundColor(255, 255, 255)
                    ->color(0, 0, 0)
                    ->generate($qrData);

// On convertit le SVG en base64 pour que DomPDF le lise comme une image classique
$qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeRaw);

// 3. Envoi à la vue
$pdf = Pdf::loadView('etudiant.pdf.pre_inscription', compact('etudiant', 'inscription', 'qrCode'));
return $pdf->stream('Fiche_Pre_Inscription_' . $etudiant->matricule . '.pdf');

}
    // ---------------------------------------------------------------
    // 2. FICHE D'INSCRIPTION VALIDÉE PDF (DÉFINITIVE — DOSSIER VALIDE)
    // ---------------------------------------------------------------
    public function genererPdfInscriptionDefinitive()
    {
        $etudiant = $this->getEtudiantSecurise();

        $inscription = Inscription::with('filiere')
                                   ->where('id_etudiant', $etudiant->id_etudiant)
                                   ->where('statut_dossier', 'valide')
                                   ->first();

        if (!$inscription) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Fiche définitive disponible uniquement après validation de la scolarité.');
        }

        $pdf = Pdf::loadView('etudiant.pdf.inscription_definitive', compact('etudiant', 'inscription'));
        return $pdf->stream('Fiche_Inscription_Valide_' . $etudiant->matricule . '.pdf');
    }

    // ---------------------------------------------------------------
    // RELEVÉ DE NOTES PDF
    // ---------------------------------------------------------------
   /**
     * 1. AFFICHER LA VUE CÔTÉ SERVEUR (Page Web HTML)
     * Cette fonction charge l'interface avec la feuille de style du site
     */
    public function afficherReleve()
    {
        $etudiant = $this->getEtudiantSecurise();

        $inscription = Inscription::where('id_etudiant', $etudiant->id_etudiant)
                                   ->where('statut_dossier', 'valide')
                                   ->first();

        if (!$inscription) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Relevé disponible uniquement après validation de votre dossier.');
        }

        $notes = Notes::where('id_etudiant', $etudiant->id_etudiant)
                      ->where('is_published', true)
                      ->with('matiere')
                      ->get();

        if ($notes->isEmpty()) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Aucune note publiée disponible pour le moment.');
        }

        // Renvoie vers ton fichier principal avec l'interface étudiante
        return view('etudiant.pdf.releve', compact('etudiant', 'notes'));
    }

    /**
     * 2. GÉNÉRER ET TÉLÉCHARGER LE PDF (Fichier brut)
     * Cette fonction utilise la vue épurée pour fabriquer le document téléchargeable
     */
    public function genererPDF()
    {
        $etudiant = $this->getEtudiantSecurise();

        $inscription = Inscription::where('id_etudiant', $etudiant->id_etudiant)
                                   ->where('statut_dossier', 'valide')
                                   ->first();

        if (!$inscription) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Relevé disponible uniquement après validation de votre dossier.');
        }

        $notes = Notes::where('id_etudiant', $etudiant->id_etudiant)
                      ->where('is_published', true)
                      ->with('matiere')
                      ->get();

        if ($notes->isEmpty()) {
            return redirect()->route('etudiant.dashboard')
                             ->with('error', 'Aucune note publiée disponible pour le moment.');
        }

        // On charge la vue minimaliste dédiée exclusivement au rendu PDF
        $pdf = Pdf::loadView('etudiant.pdf.releve_pdf', compact('etudiant', 'notes'));

        // On utilise ->download() pour lancer le téléchargement immédiat du fichier
        return $pdf->download('Releve_' . $etudiant->matricule . '.pdf');
    }



// ═══════════════════════════════════════════════════════════════
//  À AJOUTER dans votre contrôleur (ex: EtudiantController.php
//  ou un ReleveController dédié)
//
//  Prérequis : composer require barryvdh/laravel-dompdf
//  Puis ajouter dans config/app.php providers :
//      Barryvdh\DomPDF\ServiceProvider::class,
//  Et alias :
//      'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
// ═══════════════════════════════════════════════════════════════



// ── Route correspondante (web.php) ──
// Route::get('/super-admin/etudiants/{id}/releve-pdf', [EtudiantController::class, 'telechargerReleve'])
//      ->name('superadmin.pdf.releves');


public function telechargerReleve(Request $request, $id)
{
    $etudiant = Etudiant::with([
        'filiere.departement.etablissement',
        'notes.matiere',
    ])->findOrFail($id);

    // ── Paramètres dynamiques (config panel) ──
    $semestre      = $request->get('semestre', '');
    $anneeAcademique = $request->get('annee', '2025-2026');
    $sessionActive = $request->get('session', 'Normale');
    $signataire    = $request->get('signataire', 'Dr. Landry AZOGAN');
    $download      = $request->boolean('download', false);

    // ── Filtrer les notes ──
    $query = $etudiant->notes()->with('matiere');
    if ($semestre) {
        $query->whereHas('matiere', fn($q) => $q->where('semestre', $semestre));
    }
    if ($sessionActive) {
        $query->where('session', strtolower($sessionActive));
    }
    $notes = $query->get();

    // ── Calculs ──
    $totalCredits      = $notes->sum(fn($n) => $n->matiere->credit_matiere ?? 1);
    $creditsValides    = $notes->where('valeur_note', '>=', 10)->sum(fn($n) => $n->matiere->credit_matiere ?? 1);
    $totalPoints       = $notes->sum(fn($n) => $n->valeur_note * ($n->matiere->credit_matiere ?? 1));
    $moyenneGenerale   = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;

    $mention = match(true) {
        $moyenneGenerale >= 16 => 'Très Bien',
        $moyenneGenerale >= 14 => 'Bien',
        $moyenneGenerale >= 12 => 'Assez Bien',
        $moyenneGenerale >= 10 => 'Passable',
        default                => 'Insuffisant',
    };

    $codeDocument = 'UAC-' . strtoupper(substr(md5($etudiant->id_etudiant . now()), 0, 12));

    // ── QR Code (simple image base64 ou librairie) ──
    // composer require bacon/bacon-qr-code
    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(80)->generate('https://uac-hub.bj/verify/' . $codeDocument);

    // ── Données compactes ──
    $data = compact(
        'etudiant', 'notes', 'anneeAcademique', 'sessionActive',
        'signataire', 'creditsValides', 'moyenneGenerale', 'mention',
        'codeDocument', 'qrCode',
    );
    $data['totalCreditsTableau'] = $totalCredits;

    // ── Requête AJAX → retourne le HTML pour l'aperçu ──
    if ($request->ajax()) {
        $html = view('superadmin.etudiants.releve-contenu', $data)->render();
        return response()->json(['html' => $html]);
    }

    // ── Génération DomPDF ──
    $pdf = Pdf::loadView('superadmin.etudiants.releve-contenu', $data)
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'defaultFont'       => 'DejaVu Sans',
            'isRemoteEnabled'   => true,   // pour charger les images (logo, tampon)
            'isHtml5ParserEnabled' => true,
            'dpi'               => 150,
        ]);

    $filename = 'releve_' . strtolower($etudiant->nom) . '_' . date('Y') . '.pdf';

    // Téléchargement ou affichage inline
    return $download
        ? $pdf->download($filename)
        : $pdf->stream($filename);
}




}
