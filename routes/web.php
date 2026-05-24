<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthEtudiantController;
use App\Http\Controllers\EtudiantsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\EnseignantsController;
use App\Http\Controllers\InscriptionController;
// ═══════════════════════════════════════════════
// ACCUEIL
// ═══════════════════════════════════════════════
Route::get('/', function () {
    return view('welcome');
})->name('home');


// ═══════════════════════════════════════════════
// AUTHENTIFICATION (PUBLIC)
// ═══════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/connexion',  [AuthEtudiantController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthEtudiantController::class, 'login'])->name('login.store');

    Route::get('/inscription',  [AuthEtudiantController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthEtudiantController::class, 'register'])->name('register.store');

    Route::get('/activation',  [AuthEtudiantController::class, 'showActivationForm'])->name('account.activate');
    Route::post('/activation', [AuthEtudiantController::class, 'processActivation'])->name('account.activate.store');
});

Route::post('/deconnexion', [AuthEtudiantController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');






// ═══════════════════════════════════════════════
// ESPACE ÉTUDIANT
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'etudiant.only'])->group(function () {

    // Dashboard
    Route::get('/mon-espace', [EtudiantsController::class, 'index'])
         ->name('etudiant.dashboard');

    // Soumission du dossier
    Route::post('/mon-espace/completement', [EtudiantsController::class, 'storeProfile'])
         ->name('etudiant.update_profile');

    // ✅ PDF — accessibles UNIQUEMENT si inscription validée (contrôlé dans le contrôleur)
    Route::get('/mon-espace/telecharger-fiche', [EtudiantsController::class, 'genererFicheInscription'])
         ->name('etudiant.telecharger_fiche');



});


// ---------------------------------------------------------------
// Routes Espace Étudiant — à ajouter dans routes/web.php
// ---------------------------------------------------------------
// Middleware requis : auth + etudiant (vérifier type_utilisateur)
// Exemple de middleware personnalisé : 'etudiant' => CheckEtudiant::class


Route::middleware(['auth'])->prefix('etudiant')->name('etudiant.')->group(function () {



/*-------------------------------------------------------------------------
    | ESPACE ÉTUDIANT (Pré-inscription & AJAX)
    |--------------------------------------------------------------------------*/
    // Affichage de la page de pré-inscription / Fiche
    Route::get('/inscription', [InscriptionController::class, 'index'])->name('inscription.index');

    // Soumission AJAX du formulaire d'inscription (Traitement Fetch)
    Route::post('/dossier/soumettre', [InscriptionController::class, 'stockerInscription'])->name('inscription.soumettre');


    /*-------------------------------------------------------------------------
    | ESPACE ADMINISTRATION (Validation des Dossiers)
    |--------------------------------------------------------------------------*/
    // Action de validation définitive du dossier et attribution du N° d'attestation
    Route::post('/admin/inscription/{id}/valider', [InscriptionController::class, 'validerInscription'])->name('admin.inscription.valider');

    // Optionnel : Génération ou téléchargement du PDF de l'Attestation Officielle
    Route::get('/inscription/{id}/attestation/pdf', [InscriptionController::class, 'telechargerAttestation'])->name('inscription.attestation.pdf');


    // Dashboard principal
    Route::get('/dashboard', [EtudiantsController::class, 'index'])->name('etudiant.dashboard');
    Route::get('/dashboard',          [EtudiantsController::class, 'index'])->name('dashboard');
// Route pour la fiche provisoire (Dossier en attente)
    Route::get('/pdf/pre-inscription', [EtudiantsController::class, 'genererPdfPreInscription'])->name('pdf.pre_inscription');

    // Route pour la fiche définitive (Dossier validé)
    Route::get('/pdf/inscription-definitive', [EtudiantsController::class, 'genererPdfInscriptionDefinitive'])->name('pdf.inscription_definitive');
    // Dossier d'inscription
    // Remplacez [EtudiantsController::class, 'store'] par :
Route::post('dossier/soumettre', [InscriptionController::class, 'soumettre'])->name('etudiant.dossier.store');
    Route::get('/dossier',            [EtudiantsController::class, 'dossier'])           ->name('dossier');
    Route::post('/dossier',           [EtudiantsController::class, 'storeProfile'])      ->name('dossier.store');

    // Notes
    Route::get('/notes',              [EtudiantsController::class, 'notes'])             ->name('notes');

    // Examens
    Route::get('/examens',            [EtudiantsController::class, 'examens'])           ->name('examens');

    // Paramètres
    Route::get('/parametres',         [EtudiantsController::class, 'parametres'])        ->name('parametres');
    Route::post('/parametres/password',[EtudiantsController::class, 'updatePassword'])   ->name('parametres.password');

    // Téléchargements PDF
    Route::get('/pdf/releve',         [EtudiantsController::class, 'afficherReleve'])        ->name('releve.pdf');

    Route::get('/pdf/releve-pdf', [EtudiantsController::class, 'genererPDF'])->name('pdf.releve_pdf'); // Le nom doit coller à ton Blade

   // Route::get('/pdf/inscription',    [EtudiantsController::class, 'genererFicheInscription'])->name('pdf.inscription');
     //Route::get('/mon-espace/releve-pdf', [EtudiantsController::class, 'genererPDF'])->name('etudiant.releve.pdf');
});



// ═══════════════════════════════════════════════
// ESPACE CHEF SCOLARITÉ
// ═══════════════════════════════════════════════
Route::middleware('auth')->prefix('admin', 'is_admin')->name('admin.')->group(function () {
    Route::get('/dashboard',             [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/dossier/{id}/valider', [AdminController::class, 'validerDossier'])->name('valider');
    Route::post('/dossier/{id}/rejeter', [AdminController::class, 'rejeterDossier'])->name('rejeter');
    Route::post('/matiere',              [AdminController::class, 'storeMatiere'])->name('storeMatiere');
    Route::delete('/matiere/{id}',       [AdminController::class, 'destroyMatiere'])->name('destroyMatiere');
    Route::post('/notes',                 [AdminController::class, 'storeNote'])->name('storeNote');
     Route::post('/notes',                 [AdminController::class, 'create'])->name('storeNote');
});







/*
|--------------------------------------------------------------------------
| Routes Enseignant — toutes les fonctions dans EnseignantController
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_enseignant'])
    ->prefix('enseignant')
    ->name('enseignant.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [EnseignantsController::class, 'index'])
             ->name('index');

        // Notes
        Route::get('/notes/saisie/{id_matiere}',        [EnseignantsController::class, 'saisieNotes'])         ->name('notes.saisie');
        Route::post('/notes/store/{id_matiere}',         [EnseignantsController::class, 'storeNotes'])          ->name('notes.store');
        Route::post('/notes/soumettre/{id_matiere}',     [EnseignantsController::class, 'soumettreAuSuperAdmin'])->name('notes.soumettre');

        // Étudiants
        Route::get('/etudiants/liste/{id_matiere}',     [EnseignantsController::class, 'listeEtudiants'])      ->name('etudiants.liste');
        Route::get('/etudiants/export/{id_matiere}',    [EnseignantsController::class, 'exportEtudiants'])     ->name('etudiants.export');
        Route::get('/etudiants/export-all',             [EnseignantsController::class, 'exportAll'])           ->name('etudiants.export.all');
    });






// ═══════════════════════════════════════════════
// ESPACE ENSEIGNANT
// ═══════════════════════════════════════════════
//Route::middleware(['auth', 'is_enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
  //  Route::get('/dashboard', [EnseignantsController::class, 'index'])->name('dashboard');
 //   Route::get('/notes/saisie/{id}', [EnseignantsController::class, 'saisieNotes'])->name('notes.saisie');
   // Route::post('/notes/store/{id}', [EnseignantsController::class, 'storeNotes'])->name('notes.store');
  //  Route::post('/notes/soumettre/{id}', [EnseignantsController::class, 'soumettreAuSuperAdmin'])->name('notes.soumettre');
  //  Route::post('/notes/soumettre/{id}', [EnseignantsController::class, 'soumettreAuAdmin'])->name('notes.soumettre');
   // Route::get('/matiere/{id}/etudiants', [EnseignantsController::class, 'listeEtudiants'])->name('etudiants.liste');
   // Route::get('/matiere/{id}/export-excel', [EnseignantsController::class, 'exportExcel'])->name('etudiants.export');
  //  Route::get('/enseignant/etudiants/{id}', [EnseignantsController::class, 'listeEtudiants'])->name('enseignant.etudiants.liste');
//});

// ═══════════════════════════════════════════════
// ESPACE SUPER ADMIN
// ═══════════════════════════════════════════════
Route::middleware('auth','is_superadmin')->prefix('super-admin')->name('superadmin.')->group(function () {

    Route::get('/dashboard',                  [SuperAdminController::class, 'dashboard'])->name('dashboard');
Route::get(
    '/notes/export/excel',
    [SuperAdminController::class, 'exportExcel']
)->name('notes.export.excel');



// Académique
    Route::get('/academics',                  [SuperAdminController::class, 'manageAcademics'])->name('academics');
    Route::post('/departement',               [SuperAdminController::class, 'storeDepartement'])->name('departement.store');
    Route::delete('/departement/{id}',        [SuperAdminController::class, 'deleteDepartement'])->name('departement.delete');
    Route::post('/filiere',                   [SuperAdminController::class, 'storeFiliere'])->name('filiere.store');
    Route::delete('/filiere/{id}',            [SuperAdminController::class, 'deleteFiliere'])->name('filiere.delete');



    // Matières
    Route::get('/matieres',                   [SuperAdminController::class, 'manageMatieres'])->name('matieres');
    Route::post('/matieres',                  [SuperAdminController::class, 'storeMatiere'])->name('matieres.store');
    Route::put('/matieres/{id}',              [SuperAdminController::class, 'updateMatiere'])->name('matieres.update');
    Route::delete('/matieres/{id}',           [SuperAdminController::class, 'deleteMatiere'])->name('matieres.delete');


Route::get('/matieres/{id}/delete', [SuperAdminController::class, 'deleteMatiere'])->name('matieres.delete');
    // Suppression physique finale en BDD
    Route::delete('/matieres/{id}', [SuperAdminController::class, 'destroyMatiere'])->name('matieres.destroy');

    // ── ATTRIBUTIONS ENSEIGNANTS ──
    Route::get('/attributions', [SuperAdminController::class, 'manageAttributions'])->name('attributions');
    Route::post('/attributions', [SuperAdminController::class, 'storeAttribution'])->name('attributions.store');
    Route::post('/attributions/detach', [SuperAdminController::class, 'detachAttribution'])->name('attributions.detach');


    // Enseignants
    Route::get('/enseignants',                [SuperAdminController::class, 'manageEnseignants'])->name('enseignants');
    Route::post('/enseignants',               [SuperAdminController::class, 'storeEnseignant'])->name('enseignants.store');
    Route::delete('/enseignants/{id}',        [SuperAdminController::class, 'deleteEnseignant'])->name('enseignants.delete');



    // Notes
    Route::get('/notes/etudiants', [SuperAdminController::class, 'getEtudiantsParMatiere']);
    Route::get('/notes',                      [SuperAdminController::class, 'manageNotes'])->name('notes');
    Route::post('/notes',                     [SuperAdminController::class, 'storeNote'])->name('notes.store');
    Route::put('/notes/{id}',                 [SuperAdminController::class, 'updateNote'])->name('notes.update');
    Route::delete('/notes/{id}',              [SuperAdminController::class, 'deleteNote'])->name('notes.delete');
    Route::post('/notes/approve/{id}',        [SuperAdminController::class, 'approveNotes'])->name('notes.approve');
Route::post('/notes/reject/{id}',[SuperAdminController::class, 'rejectNotes'])->name('notes.reject');

Route::post('/notes/validate/{id_matiere}', [SuperAdminController::class, 'validateNotes'])->name('notes.validate');
    Route::post('/notes/publish/{id_matiere}', [SuperAdminController::class, 'publishNotes'])->name('notes.publish');
Route::get('/notes/pdf/releves/{id}', [SuperAdminController::class, 'generatePdfReleves'])->name('pdf.releves');
Route::get('/etudiants/{id}/telecharger-releve', [SuperAdminController::class, 'telechargersReleve'])->name('etudiants.telecharger-releve');

// Inscriptions
    Route::get('/inscriptions',               [SuperAdminController::class, 'manageInscriptions'])->name('inscriptions');
    Route::post('/inscriptions/valider/{id}', [SuperAdminController::class, 'validerInscription'])->name('inscriptions.valider');
    Route::post('/inscriptions/rejeter/{id}', [SuperAdminController::class, 'rejeterInscription'])->name('inscriptions.rejeter');
Route::post('/notes/lock/{id}',[SuperAdminController::class, 'lockNotes'])->name('notes.lock');




Route::post('/notes/unlock/{id}',[SuperAdminController::class, 'unlockNotes'])->name('notes.unlock');
    // ✅ Période d'inscription

    /*-------------------------------------------------------------------------
| ESPACE SUPERADMIN : GESTION DES CAMPAGNES D'INSCRIPTION
|--------------------------------------------------------------------------*/

    // Route pour ouvrir la période depuis le formulaire
    Route::post('/dashboard', [SuperAdminController::class, 'gererPeriode'])->name('dashboard');

    // Route pour couper les inscriptions instantanément
    Route::post('/inscriptions/fermer', [SuperAdminController::class, 'fermerInscriptions'])->name('inscriptions.fermer');


  //  Route::post('/periode',                   [SuperAdminController::class, 'gererPeriode'])->name('periode.store');
  //  Route::post('/periode/fermer',            [SuperAdminController::class, 'fermerInscriptions'])->name('periode.fermer');
//Route::get('/inscriptions/archive', [SuperAdminController::class, 'inscriptionsArchive'])->name('inscriptions.archive');




Route::get('/super-admin/inscriptions/archive', [SuperAdminController::class, 'archivesInscriptions'])
    ->name('superadmin.inscriptions_archive');
    Route::get('/etudiants/create', [SuperAdminController::class, 'create'])->name('etudiants.create');
Route::post('/deliberations/generate',[SuperAdminController::class,'generateDeliberations'])->name('deliberations.generate');
    // ✅ Export étudiants
    Route::get('/etudiants',                  [SuperAdminController::class, 'listeEtudiantsParFiliere'])->name('etudiants');
    Route::get('/etudiants/{id}',             [SuperAdminController::class, 'etudiantsFiliere'])->name('etudiants.filiere');
    Route::get('/etudiants/{id}/excel',       [SuperAdminController::class, 'exportEtudiantsExcel'])->name('etudiants.excel');
    Route::get('/etudiants/{id}/pdf',         [SuperAdminController::class, 'exportEtudiantsPdf'])->name('etudiants.pdf');
Route::post('/etudiants', [SuperAdminController::class, 'store'])->name('etudiants.store');
    // ✅ Relevé de notes par étudiant
Route::post('/etudiants/{id_etudiant}/toggle-status', [SuperAdminController::class, 'toggleStatus']);

// ── Aperçu du relevé (page + AJAX live)
Route::get('/etudiants/{id_etudiant}/releve',
    [SuperAdminController::class, 'releveOfficiel'])
    ->name('releve.officiel');

// ── Génération PDF simple (méthode relevePdf)
Route::get('/etudiants/{id_etudiant}/releve-pdf',
    [SuperAdminController::class, 'relevePdf'])
    ->name('pdf.releves');

// ── Téléchargement PDF avec paramètres config panel (méthode telechargersReleve)
Route::get('/notes/pdf/releves/{id}',
    [SuperAdminController::class, 'telechargersReleve'])
    ->name('pdf.telecharger');


    Route::get('/releve/{id}',                [SuperAdminController::class, 'telechargerReleve'])->name('releves');

Route::get('/releves/{id_etudiant}',[SuperAdminController::class, 'generateReleve'])->name('releves.pdf');
Route::prefix('super-admin')->name('superadmin.')->group(function () {

    Route::post( '/session/verrouiller', [SessionController::class, 'verrouiller'])->name('session.verrouiller');
});
Route::post('/etudiants/update-note', [SuperAdminController::class, 'updateNote'])->name('etudiants.updateNote');
Route::get('/notes/export/excel',[SuperAdminController::class,'exportExcel'])->name('notes.export.excel');


});


Route::get('/super-admin/stats', [SuperAdminController::class, 'getStatsJson'])
        ->name('superadmin.stats');

    // 2. Route AJAX pour ouvrir/fermer la période d'inscription (Méthode POST)
    Route::post('/super-admin/toggle-inscription', [SuperAdminController::class, 'toggleInscription'])
        ->name('superadmin.toggleInscription');

// Route AJAX pour récupérer les filières d'un département
Route::get('/super-admin/get-filieres/{id_departement}', [SuperAdminController::class, 'getFilieresByDepartement'])
    ->name('superadmin.getFilieres');
Route::get('/superadmin/get-departements', [SuperAdminController::class, 'getDepartements'])->name('superadmin.getDepartements');
// Route pour afficher et filtrer dynamiquement le relevé de notes officiel via AJAX
Route::get('/super-admin/etudiants/{id_etudiant}/releve', [SuperAdminController::class, 'releveOfficiel'])
    ->name('superadmin.etudiants.releve');
Route::prefix('super-admin')
    ->name('superadmin.')
    ->middleware(['auth'])
    ->group(function () {

    Route::post('notes/store-all', [SuperAdminController::class, 'storeAll'])
     ->name('superadmin.notes.storeAll');




// Remplace {id} par {id_etudiant}
Route::post('/super-admin/etudiants/{id_etudiant}/toggle-status', [SuperAdminController::class, 'toggleStatus']);
     // ═══════════════════════════════════════
    // ÉTUDIANTS
    // ═══════════════════════════════════════

    Route::prefix('etudiants')
        ->name('etudiants.')
        ->group(function () {

        // 📋 Liste étudiants
        Route::get('/',
            [SuperAdminController::class, 'indexEtudiants']
        )->name('index');

        // 👁 Voir profil étudiant
        Route::get('/{id}',
            [SuperAdminController::class, 'showEtudiant']
        )->name('show');

        // ✏ Formulaire modification
        Route::get('/{id}/edit',
            [SuperAdminController::class, 'editEtudiant']
        )->name('edit');

        // 💾 Mise à jour
        Route::put('/{id}',
            [SuperAdminController::class, 'updateEtudiant']
        )->name('update');

        // ❌ Supprimer étudiant
        Route::delete('/{id}',
            [SuperAdminController::class, 'destroyEtudiant']
        )->name('destroy');

        // 🔒 Bloquer étudiant
        Route::post('/{id}/bloquer',
            [SuperAdminController::class, 'bloquerEtudiant']
        )->name('bloquer');

        // 🔓 Débloquer étudiant
        Route::post('/{id}/debloquer',
            [SuperAdminController::class, 'debloquerEtudiant']
        )->name('debloquer');

        // 📘 Voir notes étudiant
        Route::get('/{id}/notes',
            [SuperAdminController::class, 'notesEtudiant']
        )->name('notes');

        // 📄 Télécharger relevé PDF
        Route::get('/{id}/releve',
            [SuperAdminController::class, 'telechargerReleve']
        )->name('releve');

    });

});

// On suppose que toutes ces routes sont protégées par tes middlewares d'authentification (ex: auth, etudiant)
Route::middleware(['auth'])->group(function () {

    /*-------------------------------------------------------------------------
    | 1. TABLEAU DE BORD (DASHBOARD)
    |--------------------------------------------------------------------------*/
    Route::get('/dashboard', [EtudiantsController::class, 'index'])->name('dashboard');


    /*-------------------------------------------------------------------------
    | 2. GESTION DU DOSSIER D'INSCRIPTION (FORMULAIRE ET ÉTAPES AJAX)
    |--------------------------------------------------------------------------*/
    // Affichage de la page du formulaire par étapes
    Route::get('/dossier', [EtudiantsController::class, 'dossier'])->name('dossier');

    // Soumission unique en AJAX (pointe vers storeProfile de EtudiantsController que nous avons corrigé)
    Route::post('/dossier/soumettre', [EtudiantsController::class, 'storeProfile'])->name('etudiant.dossier.store');


    /*-------------------------------------------------------------------------
    | 3. TÉLÉCHARGEMENTS ET GÉNÉRATION DE DOCUMENTS PDF
    |--------------------------------------------------------------------------*/
    // Fiche provisoire (Dossier en attente / préinscription) -> Utilisé par ton modal de succès
    Route::get('/pdf/pre-inscription', [EtudiantsController::class, 'genererPdfPreInscription'])->name('pdf.pre_inscription');

    // Fiche définitive (Dossier validé par la scolarité)
    Route::get('/pdf/inscription-definitive', [EtudiantsController::class, 'genererPdfInscriptionDefinitive'])->name('pdf.inscription_definitive');

    // Relevé de notes officiel de l'étudiant
    Route::get('/pdf/releve', [EtudiantsController::class, 'genererPDF'])->name('pdf.releve');


    /*-------------------------------------------------------------------------
    | 4. CONSULTATION DES NOTES ET EXAMENS
    |--------------------------------------------------------------------------*/
    Route::get('/notes', [EtudiantsController::class, 'notes'])->name('notes');
    Route::get('/examens', [EtudiantsController::class, 'examens'])->name('examens');


    /*-------------------------------------------------------------------------
    | 5. PARAMÈTRES ET SÉCURITÉ DU COMPTE
    |--------------------------------------------------------------------------*/
    Route::get('/parametres', [EtudiantsController::class, 'parametres'])->name('parametres');
    Route::post('/parametres/password', [EtudiantsController::class, 'updatePassword'])->name('parametres.password');


    /*-------------------------------------------------------------------------
    | 6. ESPACE ADMINISTRATION & VALIDATION SCOLARITÉ
    |--------------------------------------------------------------------------*/
    // Action de validation définitive du dossier (géré par l'administration)
    Route::post('/admin/inscription/{id}/valider', [InscriptionController::class, 'validerInscription'])->name('admin.inscription.valider');
});



























