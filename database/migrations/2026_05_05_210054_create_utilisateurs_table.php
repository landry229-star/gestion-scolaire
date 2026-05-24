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
        Schema::create('utilisateurs', function (Blueprint $table) {
             $table->id('id_utilisateur');
             $table->string('nom', 255);
             $table->string('prenom', 255);
              $table->string('email');
             $table->foreignId('id_etudiant')->nullable()->unique()->constrained('etudiants', 'id_etudiant')->cascadeOnDelete();
             $table->foreignId('id_personnel')->nullable()->unique()->constrained('personnels', 'id_personnel')->cascadeOnDelete();
             $table->foreignId('id_enseignant')->nullable()->unique()->constrained('enseignants', 'id_enseignant')->cascadeOnDelete();
             $table->enum('type_utilisateur', ['admin', 'etudiant', 'enseignant', 'chef_scolarite']);
             $table->string('password', 255);
             $table->date('date_creation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
