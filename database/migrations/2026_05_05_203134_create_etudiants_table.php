<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
    $table->id('id_etudiant');
    $table->string('matricule', 20)->unique()->nullable();
    $table->string('nom', 255)->nullable();
    $table->string('prenom', 255)->nullable();
    $table->string('sexe', 10)->nullable();
    $table->string('email')->unique()->nullable();
    $table->string('password')->nullable();
    $table->string('lieu_naissance', 50)->nullable();
    $table->date('date_naissance')->nullable();
    $table->string('telephone', 20)->unique()->nullable();
    $table->string('niveau', 255)->nullable();
   $table->enum('statut_compte', [
    'actif',
    'bloque'
])->default('actif');


    $table->string('document_cip')->nullable();
    $table->string('diplome_bac')->nullable();
    $table->string('photo')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
