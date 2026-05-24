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
            // 1. Niveau d'étude dans le système LMD (L1, L2, L3...)
            $table->string('niveau', 10)->nullable()->after('id_filiere');

            // 2. Régime d'étude (Cours du jour / Cours du soir)
            $table->string('regime', 50)->nullable()->after('niveau');

            // 3. Statut financier de l'étudiant (Ex: Payant, Boursier, Secouru)
            $table->string('statut_financier', 50)->default('payant')->after('regime');

            // 4. Numéro de reçu de la banque ou Id de transaction MoMo/Moov
            $table->string('reference_paiement', 100)->nullable()->after('statut_financier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn(['niveau', 'regime', 'statut_financier', 'reference_paiement']);
        });
    }
};
