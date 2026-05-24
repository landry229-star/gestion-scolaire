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
        Schema::table('etudiants', function (Blueprint $table) {
            // Ajout des nouvelles colonnes (en autorisant le NULL au départ pour ne pas bloquer les comptes existants)
            $table->string('nationalite', 100)->nullable()->after('lieu_naissance');
            $table->string('adresse', 255)->nullable()->after('nationalite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // Suppression des colonnes en cas de retour en arrière (rollback)
            $table->dropColumn(['nationalite', 'adresse']);
        });
    }
};
