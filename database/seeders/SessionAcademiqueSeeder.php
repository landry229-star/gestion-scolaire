<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades;
class SessionAcademiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('session_academiques')->insert([
        'annee' => '2025-2026',
        'statut' => 'active'
    ]);
    }
}
