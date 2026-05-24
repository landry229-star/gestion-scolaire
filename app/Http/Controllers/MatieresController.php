<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 1. IMPORTATION DES MODÈLES (Indispensable)
use App\Models\Filieres;
use App\Models\Matieres;
use App\Models\Semestres; // Si vous avez besoin des semestres aussi

class MatieresController extends Controller
{
    public function index(Request $request)
    {
        // 2. Récupérer les filières pour le filtre et le modal
        // On peut aussi récupérer les semestres si votre formulaire en a besoin
        $filieres = Filieres::all();
        $semestres = Semestres::all(); // Optionnel, selon votre vue

        // 3. Gérer le filtre avec une requête préparée
        $query = Matieres::query();

        if ($request->filled('filiere_filter')) {
            $query->where('id_filiere', $request->filiere_filter);
        }

        // On récupère les résultats
        $matieres = $query->get();

        // 4. ENVOYER LES VARIABLES À LA VUE
        // Assurez-vous que le chemin de la vue est exact
        return view('superadmin.matieres.matieres_catalogue', compact('filieres', 'matieres', 'semestres'));
    }
}
