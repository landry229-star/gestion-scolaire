<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filieres;
use App\Models\Matieres;
use App\Models\Semestres;

class MatiereFilieresSeeder extends Seeder
{
    public function run(): void
    {
         
        $filieres = Filieres::all();

        // On récupère proprement les IDs des semestres
        $s1 = Semestres::where('code_semestre', 'S1')->first();
        $s2 = Semestres::where('code_semestre', 'S2')->first();

        foreach ($filieres as $filiere) {

            // 5 matières pour le S1
            $matieresS1 = Matieres::factory()->count(5)->create([
                'id_semestre' => $s1->id_semestre,
            ]);

            // 5 matières pour le S2
            $matieresS2 = Matieres::factory()->count(5)->create([
                'id_semestre' => $s2->id_semestre,
            ]);

            // Liaison dans la table pivot
            $filiere->matieres()->attach($matieresS1->pluck('id_matiere'));
            $filiere->matieres()->attach($matieresS2->pluck('id_matiere'));

            $this->command->info("Filière {$filiere->nom_filiere} complétée avec 10 matières.");
        }
    }
}
