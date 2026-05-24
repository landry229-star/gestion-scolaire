<?php

namespace Database\Seeders;
use App\Models\Semestres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $semestres = [
            [

                'code_semestre' => 'S1',
                'description' => 'Premier semestre de l\'année académique',
            ],
            [

                'code_semestre' => 'S2',
                'description' => 'Deuxième semestre de l\'année académique',
            ],
        ];

        foreach ($semestres as $s) {
            Semestres::updateOrCreate(
                ['code_semestre' => $s['code_semestre']],
                $s
            );
        }
    }
}
