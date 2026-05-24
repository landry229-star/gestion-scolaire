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
        Schema::create('salle', function (Blueprint $table) {
            // id_salle INT AUTO_INCREMENT PRIMARY KEY
            $table->id('id_salle');

            // nom_salle VARCHAR(50) NOT NULL
            $table->string('nom_salle', 50);

            // capacite INT (nullable car non spécifié NOT NULL dans votre SQL)
            $table->integer('capacite')->nullable();
             $table->timestamps();
            // Paramètres de la table
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
