<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtilisateursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Créer les comptes pour tous les ÉTUDIANTS
        $etudiants = Etudiant::all();
        foreach ($etudiants as $etudiant) {
            DB::table('utilisateurs')->insert([
                'id_etudiant' => $etudiant->id_etudiant,
                'type_utilisateur' => 'etudiant',
                'mot_de_passe' => Hash::make('password123'), // Mot de passe identique pour les tests
                'date_creation' => now(),
            ]);
        }

        // 2. Créer un compte pour l'ADMIN (Compte fixe)
        DB::table('utilisateurs')->insert([
            'type_utilisateur' => 'admin',
            'mot_de_passe' => Hash::make('admin2026'),
            'date_creation' => now(),
        ]);

        // 3. Créer un compte pour le CHEF SCOLARITÉ
        // On suppose que tu as créé un personnel d'abord
        $chef = Personnel::where('poste', 'Chef Scolarité')->first();
        if ($chef) {
            DB::table('utilisateur')->insert([
                'id_personnel' => $chef->id_personnel,
                'type_utilisateur' => 'chef_scolarite',
                'mot_de_passe' => Hash::make('scolarite123'),
                'date_creation' => now(),
            ]);
        }
    }
}
