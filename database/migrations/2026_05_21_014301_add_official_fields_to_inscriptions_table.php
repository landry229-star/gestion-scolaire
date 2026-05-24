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
        Schema::table('inscriptions', function (Blueprint $table) {
            // Informations Académiques directes
            $table->string('entite')->nullable()->comment('Ex: FAST, FLASH, etc.');
            $table->string('annee_etude')->nullable()->comment('Ex: Licence 1, LRs1s2-MIA');

            // Statut de l'étudiant (Boursier, Exonéré, etc.)
            $table->string('statut_boursier')->default('Non Boursier');

            // Données Financières
            $table->decimal('montant_frais', 10, 2)->default(15000.00);
            $table->decimal('restant_du', 10, 2)->default(0.00);

            // Validation officielle du Rectorat (On utilise ton statut_dossier existant pour l'état)
            $table->string('numero_attestation')->nullable()->unique();
            $table->timestamp('date_validation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'entite',
                'annee_etude',
                'statut_boursier',
                'montant_frais',
                'restant_du',
                'numero_attestation',
                'date_validation'
            ]);
        });
    }
};
