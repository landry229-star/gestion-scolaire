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
        Schema::create('deliberations', function (Blueprint $table) {

            $table->id('id_deliberation');

            $table->foreignId('id_etudiant')
                  ->constrained(
                      'etudiants',
                      'id_etudiant'
                  )
                  ->cascadeOnDelete();

            $table->decimal(
                'moyenne_generale',
                5,
                2
            );

            $table->integer('rang')
                  ->nullable();

            $table->integer('credits_valides')
                  ->default(0);

            $table->enum('decision', [

                'admis',

                'ajourne',

                'redouble'

            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliberations');
    }
};
