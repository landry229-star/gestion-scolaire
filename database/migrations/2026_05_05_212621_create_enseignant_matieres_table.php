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
        Schema::create('enseignant_matieres', function (Blueprint $table) {
            $table->foreignId('id_enseignant')->constrained('enseignants', 'id_enseignant')->cascadeOnDelete();
             $table->foreignId('id_matiere')->constrained('matieres', 'id_matiere')->cascadeOnDelete();
             $table->primary(['id_enseignant', 'id_matiere']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignant_matieres');
    }
};
