<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiants; // Assure-toi que le nom correspond bien à ton modèle

class EtudiantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Génère 50 étudiants en utilisant la factory
        Etudiants::factory()->count(50)->create();
    }
}
