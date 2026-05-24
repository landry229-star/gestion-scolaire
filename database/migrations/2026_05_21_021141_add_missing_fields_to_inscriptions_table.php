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
            // Informations académiques et choix

            // Fichage des coordonnées au moment de l'inscription
            $table->string('telephone')->nullable()->after('id_etudiant');
            $table->string('adresse')->nullable()->after('telephone');
            $table->string('groupe_sanguin', 5)->nullable()->after('adresse');

            // Références du BAC d'origine (Sécurisation scolarité)
            $table->string('num_table_bac')->nullable()->after('annee_academique');
            $table->string('serie_bac', 10)->nullable()->after('num_table_bac');
            $table->integer('annee_bac')->nullable()->after('serie_bac');



        });
    }

    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn([

                'num_table_bac', 'serie_bac', 'annee_bac'
            ]);
        });
    }
};
