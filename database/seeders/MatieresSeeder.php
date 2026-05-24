<?php

namespace Database\Seeders;
use App\Models\Semestres;
use App\Models\Matieres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatieresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matieres::factory()->count(30)->create();
    }
}
