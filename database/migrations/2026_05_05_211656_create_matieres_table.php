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
        Schema::create('matieres', function (Blueprint $table) {
            $table->id('id_matiere');

            // Ajout des liaisons indispensables pour le Département et la Filière
            $table->foreignId('id_departement')->constrained('departements', 'id_departement')->cascadeOnDelete();
            $table->foreignId('id_filiere')->constrained('filieres', 'id_filiere')->cascadeOnDelete();

            // Ta liaison Semestre d'origine (mise en nullable si elle est optionnelle dans ton formulaire)
            $table->foreignId('id_semestre')->nullable()->constrained('semestres', 'id_semestre')->cascadeOnDelete();

            $table->text('nom_matiere')->nullable();
            $table->string('code_matiere', 20)->unique();
            $table->integer('credit_matiere');
            $table->boolean('notes_locked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};
