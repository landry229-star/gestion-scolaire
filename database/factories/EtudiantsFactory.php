<?php

namespace Database\Factories;

use App\Models\Etudiants;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EtudiantsFactory extends Factory
{
    protected $model = \App\Models\Etudiants::class;

    public function definition(): array
    {
        $sexe = fake()->randomElement(['M', 'F']);

        $prenom = $sexe === 'M'
            ? fake()->randomElement([
                'Frédéric', 'Pierre', 'Jean', 'Prudence', 'Kofi', 'Landry', 'Nicola', 'Ramdane',
                'Fréjus', 'Amos', 'Emmanuel', 'Samuel', 'David', 'Nathan', 'Arnaud', 'Euloge', 'Chédracques',
                'Cédric', 'Rodrigue', 'Wilfried', 'Ulrich', 'Lionel', 'Hervé', 'Ghislain', 'Yve', 'César', 'Jacques',
                'Blaise', 'Serge', 'Dominique', 'Georges', 'Ernest', 'Auguste', 'Fidèle', 'Romain', 'Jean-Baptiste'
            ])
            : fake()->randomElement([
                'Marie', 'Sophie', 'Aline', 'Fatima', 'Grace', 'Esther', 'Merveille', 'Yemima', 'Réthis',
                'Albertine', 'Rose', 'Priscille', 'Marina', 'Comforte', 'Patience', 'Leatitia', 'Bernice', 'Félicité',
                'Mireille', 'Clarisse', 'Solange', 'Bernadette', 'Chantal', 'Victoire', 'Christelle', 'Gloria', 'Génévieve',
                'Noëlla', 'Carine', 'Daniella', 'Josiane', 'Immacullée', 'Aspasie', 'Célestine', 'Aïssatou', 'Daphné',
                'Sonia', 'Mélanie', 'Nathalie', 'Adélaïde', 'Fleur', 'Claudine'
            ]);

        $nom = fake()->randomElement([
            'KOUASSI', 'AGOSSA', 'DOSSOU', 'HOUNSOU', 'AMOUSSOU', 'OFIE', 'AYAKA', 'KANVI', 'AGOSSADOU',
            'DJOSSOU', 'GNANSOUNOU', 'AZONDEKON', 'SOGLO', 'KPODE', 'TOSSA', 'KEKE', 'GNIMASSOU', 'ETEKA',
            'TOSSOU', 'BEHANZIN', 'ZINSOU', 'ADJOVI', 'AKPO', 'OGOU', 'BADA', 'TANGNI', 'AYELODJOU', 'TOFFA',
            'KOFFI', 'TOHA', 'KOUROUMA', 'MAFFON', 'SODJINOU', 'IKO', 'GBAGIDI'
        ]);

        // --- CORRECTION DE L'EMAIL ---
        // On utilise Str::slug pour nettoyer les accents et on ajoute un chiffre aléatoire
        // ou un identifiant unique pour éviter les doublons SQL.
        $emailBase = Str::slug($prenom . '.' . $nom);
        $email = $emailBase . fake()->unique()->numberBetween(1, 999) . '@etu.uac.bj';
      //$password = Hash::make('password123');

        $villesBenin = [
            'Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Bohicon', 'Djougou', 'Tanguieta', 'Savé',
            'Kandi', 'Natitingou', 'Ouidah', 'Lokossa', 'Calavi', 'Dassa-Zoumé', 'Glazoué', 'Bembèrèkè',
            'Savalou', 'Banikoara', 'Kérou', 'Pobè', 'Nikki', 'Sakété', 'Kétou', 'Bantè', 'Adjarra',
            'Zogbodomey',
        ];


        return [
            'nom' => strtoupper($nom),
            'prenom' => $prenom,
            'email' => $email, // L'email est maintenant garanti unique
            'matricule' => fake()->unique()->numerify('######') . substr(date('Y'), -2),
            'telephone' => '+229 01' . fake()->unique()->numerify('########'),
            'sexe' => $sexe,
            'date_naissance' => fake()->dateTimeBetween('-30 years', '-18 years'),
            'lieu_naissance' => fake()->randomElement($villesBenin),
            
        ];
    }
}
