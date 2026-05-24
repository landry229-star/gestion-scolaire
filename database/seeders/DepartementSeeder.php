<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departements = [
            ['nom_departement' => 'Mathématique'],
            ['nom_departement' => 'Physique-Chimie'],
            ['nom_departement' => 'Science de la vie'],
        ];

        foreach ($departements as $dept) {
            // On utilise create pour insérer chaque département
            Departement::create($dept);
        }
    }
}

