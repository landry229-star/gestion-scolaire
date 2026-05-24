<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Etudiants;
use App\Models\Matieres;
use App\Models\Notes;

class NoteSeeder extends Seeder
{
    public function run()
    {
        $etudiants = Etudiants::all();
        $matieres = Matieres::all();

        foreach ($etudiants as $etudiant) {
            foreach ($matieres as $matiere) {
                // Simulation d'une note réaliste (Moyenne autour de 12)
                $noteBase = $this->generateRealisticNote();

                Note::create([
                    'id_etudiant' => $etudiant->id_etudiant,
                    'id_matiere'  => $matiere->id_matiere,
                    'note_obtenue' => $noteBase,
                ]);
            }
        }
    }

    /**
     * Génère une note suivant une distribution plus naturelle
     */
    private function generateRealisticNote()
    {
        $chance = rand(1, 100);

        if ($chance <= 10) return rand(0, 7) + (rand(0, 9) / 10);   // 10% d'échec critique
        if ($chance <= 30) return rand(8, 10) + (rand(0, 9) / 10);  // 20% de rattrapage proche
        if ($chance <= 80) return rand(11, 15) + (rand(0, 9) / 10); // 50% de mentions Passable/Assez Bien
        return rand(16, 20);                                        // 20% d'excellentes notes
    }
}
