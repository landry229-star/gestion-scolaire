<?php

namespace Database\Factories;

use App\Models\Matieres;
use App\Models\Semestres;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatieresFactory extends Factory
{
    protected $model = Matieres::class;

    public function definition(): array
    {
        $Matieres_uac = [
            // Mathématiques & Info (MIA)
            'Analyse complexe', 'Algèbre linéaire', 'Algorithmique ', 'Logique Mathématique', 'Outils Informatiques',
            'Analyse géometrique', 'Algèbre mitilinéaire', 'Structure de Données', 'Probabilités', 'Programmation en langage C',
            // Physique-Chimie (PC)
            'Thermodynamique 1', 'Optique Géométrique', 'Mécanique du Point', 'Chimie Générale', 'Électromagnétisme',
            'Mécanique Solide', 'Optique Physique', 'Chimie Organique', 'Électronique', 'Physique Quantique',
            // Science de la Vie (SV)
            'Biologie Cellulaire', 'Botanique', 'Zoologie', 'Géologie Générale', 'Chimie Bio-organique',
            'Génétique', 'Physiologie Animale', 'Biochimie', 'Microbiologie', 'Écologie',
            // Transversal
            'Anglais litteraire', 'Techniques de Communication', 'Anglais scientifique', 'Civisme et Éthique'
        ];

        return [
            'id_semestre' => Semestres::inRandomOrder()->first()->id_semestre ?? null,
            'nom_matiere' => fake()->unique()->randomElement($Matieres_uac),
            'code_matiere' => strtoupper(fake()->unique()->bothify('???-####')),
            'credit_matiere' => fake()->randomElement([2, 3, 4, 5, 6]),
        ];
    }
}
