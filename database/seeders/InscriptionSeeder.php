<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etudiants = Etudiant::all();
    $filiereGL = \App\Models\Filieres::where('nom_filiere', 'Génie Logiciel')->first();
    $dossier = \App\Models\Dossiers::first();

    foreach ($etudiants as $etudiant) {
        \App\Models\Inscription::create([
            'id_etudiant' => $etudiant->id_etudiant,
            'id_filiere' => $filiereGL->id_filiere,
            'id_dossier' => $dossier->id_dossier,
            'annee_academique' => '2025-2026',
            'statut_dossier' => 'Validé'
        ]);
    }
    }
}
