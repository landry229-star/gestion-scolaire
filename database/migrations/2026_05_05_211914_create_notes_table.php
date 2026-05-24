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
       Schema::create('notes', function (Blueprint $table) {

    $table->id('id_note');

    $table->foreignId('id_matiere')
          ->constrained('matieres', 'id_matiere')
          ->cascadeOnDelete();

    $table->foreignId('id_etudiant')
          ->constrained('etudiants', 'id_etudiant')
          ->cascadeOnDelete();

    $table->decimal('valeur_note', 5, 2);

    $table->enum('session', [
        'normale',
        'rattrapage'
    ])->default('normale');

    $table->enum('statut', [
        'brouillon',
        'soumis',
        'valide',
        'rejete'
    ])->default('brouillon');

    $table->boolean('is_published')->default(false);

    $table->timestamp('published_at')->nullable();

    $table->foreignId('validated_by')
          ->nullable()
          ->constrained('users');

    $table->timestamp('validated_at')->nullable();

    $table->text('motif_rejet')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
