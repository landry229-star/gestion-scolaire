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
        Schema::create('bilans', function (Blueprint $table) {
            $table->id('id_bilan');
            $table->foreignId('id_etudiant')->constrained('etudiants', 'id_etudiant');
            $table->foreignId('id_semestre')->constrained('semestres', 'id_semestre');
            $table->decimal('moyenne_semestrielle', 4, 2);
            $table->integer('total_credits_valides');
            $table->enum('resultat', ['Admis', 'Ajourné', 'Rattrapage']);
            $table->string('observation')->nullable(); // Ex: "Mention Bien"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilans');
    }
};
