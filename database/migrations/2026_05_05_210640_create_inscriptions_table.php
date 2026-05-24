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
    { Schema::create('inscriptions', function (Blueprint $table) {
    $table->id('id_inscription');
    $table->foreignId('id_etudiant')->constrained('etudiants', 'id_etudiant')->cascadeOnDelete();
    $table->foreignId('id_filiere')->nullable()->constrained('filieres', 'id_filiere')->nullOnDelete();
    $table->string('annee_academique', 50)->nullable();
    $table->string('statut_dossier', 50)->default('en_attente'); // 'en_attente', 'valide', 'rejete'
    $table->date('date_inscription')->nullable();
    $table->text('motif_rejet')->nullable(); // À ajouter dans la table inscriptions
    // ON A SUPPRIMÉ id_dossier ICI

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
