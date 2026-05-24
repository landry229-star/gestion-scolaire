<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Personnels;
use App\Models\User;

class PersonnelsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer le personnel
        $personnel = Personnels::create([
            'nom'      => 'AZOGAN',
            'prenom'   => 'Landry',
            'email'    => 'admin@uac.bj',
            'sexe'     => 'M',
            'post'     => 'Chef Scolarité',
            'photo'    => null,
            'password' => Hash::make('password123'), // ✅ champ manquant ajouté
        ]);

        // 2. Créer son compte utilisateur lié
        User::create([
            'nom'              => $personnel->nom,
            'prenom'           => $personnel->prenom,
            'email'            => $personnel->email,
            'password'         => Hash::make('password123'),
            'type_utilisateur' => 'chef_scolarite',
            'id_personnel'     => $personnel->id_personnel,
        ]);
    }
}
