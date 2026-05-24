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
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id('id_enseignant');
            $table->foreignId('id_departement')->constrained('departements', 'id_departement')->cascadeOnDelete();
            $table->string('nom', 255)->nullable();
            $table->string('prenom', 255)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->text('email')->unique();
            $table->enum('sexe', ['F', 'M']);
            $table->string('grade_enseignant', 255)->nullable();
            $table->binary('photo')->unique()->nullable();
            $table->timestamps();
        });
    }
    /** Un enseignant enseigne plusieurs matières */
public function matieres()
{
    return $this->belongsToMany(
        Matieres::class,
        'enseignant_matieres',
        'id_enseignant',
        'id_matiere'
    );
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
