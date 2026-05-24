<?php

namespace Database\Seeders;
use App\Models\Departement;
use App\Models\Filieres;
use Illuminate\Database\Seeder;

class FilieresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. On récupère les départements existants pour avoir leurs IDs
        $deptMath = Departement::where('nom_departement', 'Mathématique')->first();
        $deptPC = Departement::where('nom_departement', 'Physique-Chimie')->first();
        $deptSV = Departement::where('nom_departement', 'Science de la vie')->first();

        // 2. On définit les filières avec les bonnes relations
        $filieres = [
            // Filières pour Mathématique
            [
                'id_departement' => $deptMath?->id_departement,
                'nom_filiere' => 'Mathématique Informatique Application',
                'code_filiere' => 'MIA',
                'description' => 'Formation axée sur les mathématiques pures et le développement informatique.',
            ],
            // Filières pour Physique-Chimie
            [
                'id_departement' => $deptPC?->id_departement,
                'nom_filiere' => 'Physique-Chimie',
                'code_filiere' => 'PC',
                'description' => 'Étude des phénomènes physiques et des structures chimiques.',
            ],
            // Filières pour Science de la vie
            [
                'id_departement' => $deptSV?->id_departement,
                'nom_filiere' => 'Biologie',
                'code_filiere' => 'SVT',
                'description' => 'Sciences biologiques, génétique et étude du vivant.',
            ],
        ];

        foreach ($filieres as $filiere) {
            // On vérifie que le département existe avant d'insérer
            if ($filiere['id_departement'] !== null) {
                Filieres::create($filiere);
            }
        }
    }
}
