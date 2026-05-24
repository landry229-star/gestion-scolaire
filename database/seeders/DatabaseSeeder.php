<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
$this->call([
        DepartementSeeder::class,
        FilieresSeeder::class,
        SemestresSeeder::class,
        ExamenSeeder::class,
        MatiereFilieresSeeder::class, // Les matières sont créées et liées ici
        EnseignantsSeeder::class,      // On affecte les profs aux matières ici
        EtudiantsSeeder::class,
    ]);

    }

}
