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
        Schema::create('documents', function (Blueprint $table) {
             $table->id('id_document');
            $table->foreignId('id_etudiant')->constrained('etudiants','id_etudiant')->cascadeOnDelete();
            $table->enum('type_document',['fiche_preinscription','attestation_inscription','releve_note','cursus']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
