<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaiementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On récupère toutes les inscriptions validées
        $inscriptions = Inscription::all();

        foreach ($inscriptions as $inscription) {
            Paiement::create([
                'id_inscription' => $inscription->id_inscription,
                'type_paiement' => collect(['UBA', 'BOA', 'EcoBank'])->random(), // Choix réaliste
                'statut_paiement' => 'paye',
                'montant' => 25000.00,
                'recu_chemin' => 'storage/recus/2026/recu_' . $inscription->id_inscription . '.pdf',
            ]);
        }
    }
}
