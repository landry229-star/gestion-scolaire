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
        Schema::create('etudiant_examans', function (Blueprint $table) {
           $table->foreignId('id_etudiant')->constrained('etudiants', 'id_etudiant')->cascadeOnDelete();
             $table->foreignId('id_examen')->constrained('examens', 'id_examen')->cascadeOnDelete();
             $table->primary(['id_etudiant', 'id_examen']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiant_examans');
    }
};
