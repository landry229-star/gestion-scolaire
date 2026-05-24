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
        Schema::create('matiere_filieres', function (Blueprint $table) {
            $table->foreignId('id_matiere')->constrained('matieres', 'id_matiere')->cascadeOnDelete();
            $table->foreignId('id_filiere')->constrained('filieres', 'id_filiere')->cascadeOnDelete();
            $table->primary(['id_matiere', 'id_filiere']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matiere_filieres');
    }
};
