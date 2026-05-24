<?php

namespace Database\Seeders;

use App\Models\Enseignants;
use App\Models\Departement;
use App\Models\Filieres;
use App\Models\Matieres;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnseignantsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nettoyage de la table pivot (Note le 's' à matieres)
        Schema::disableForeignKeyConstraints();
        DB::table('enseignant_matieres')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['nom' => 'DEDO', 'prenom' => 'Marcel', 'tel' => '+2290199346509', 'sexe' => 'M'],
            ['nom' => 'GUEDENON', 'prenom' => 'Alida', 'tel' => '+2290194349509', 'sexe' => 'F'],
            ['nom' => 'AZIZA', 'prenom' => 'Pascal', 'tel' => '+2290199356587', 'sexe' => 'M'],
            ['nom' => 'AZOG', 'prenom' => 'Martin', 'tel' => '+2290195347509', 'sexe' => 'M'],
            ['nom' => 'GOGBE', 'prenom' => 'Charlemangne', 'tel' => '+2290199345509', 'sexe' => 'M'],
            ['nom' => 'TAGNI', 'prenom' => 'Romaric', 'tel' => '+2290195346529', 'sexe' => 'M'],
            ['nom' => 'KOHO', 'prenom' => 'Marcaire', 'tel' => '+2290196346594', 'sexe' => 'M'],
            ['nom' => 'AZOGAN', 'prenom' => 'Hypolite', 'tel' => '+2290199346520', 'sexe' => 'M'],
            ['nom' => 'ZINSOU', 'prenom' => 'Barnabe', 'tel' => '+2290144346509', 'sexe' => 'M'],
        ];

        $departements = Departement::all();
        $filieres = Filieres::all();
        $grades = ['Maître Assistant (MA)', 'Professeur Titulaire (PT)', 'Maître de Conférence (MC)'];

        if ($departements->isEmpty() || $filieres->isEmpty()) {
            $this->command->error("Erreur : Départements ou Filières manquants.");
            return;
        }

        // --- ÉTAPE 1 : CRÉATION DES ENSEIGNANTS ---
        $enseignantsCrees = collect();

        foreach ($data as $index => $item) {
            $dept = $departements[$index % $departements->count()];
            $gradeAleatoire = $grades[array_rand($grades)];
            $email = Str::slug($item['prenom'] . '.' . $item['nom']) . $index . '@uac.bj';

            $enseignant = Enseignants::updateOrCreate(
                ['email' => $email],
                [
                    'id_departement' => $dept->id_departement,
                    'nom' => $item['nom'],
                    'prenom' => $item['prenom'],
                    'telephone' => $item['tel'],
                    'sexe' => $item['sexe'],
                    'grade_enseignant' => $gradeAleatoire,
                ]
            );
            $enseignantsCrees->push($enseignant);
        }

        // --- ÉTAPE 2 : ATTRIBUTION DES MATIÈRES ---
        foreach ($filieres as $fIndex => $filiere) {
            // On récupère les 3 profs dédiés à cette filière
            // (MIA : index 0,1,2 | Science de Vie : index 3,4,5 | etc.)
            $start = $fIndex * 3;
            $equipe = $enseignantsCrees->slice($start, 3)->values();

            // On récupère les matières de cette filière spécifique
            $matieresDeLaFiliere = $filiere->matieres;

            if ($matieresDeLaFiliere->isEmpty()) {
                $this->command->warn("Pas de matières pour la filière {$filiere->nom_filiere}");
                continue;
            }

            foreach ($matieresDeLaFiliere as $mIndex => $matiere) {
                if ($equipe->isNotEmpty()) {
                    // On choisit un des 3 profs de l'équipe (rotation)
                    $prof = $equipe[$mIndex % $equipe->count()];

                    // On attache le prof à la matière (syncWithoutDetaching évite les doublons)
                    $matiere->enseignants()->syncWithoutDetaching([$prof->id_enseignant]);
                }
            }

            $this->command->info("Succès : Enseignants liés à la filière {$filiere->nom_filiere}.");
        }
    }
}
