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
       Schema::create('historique_notes', function (Blueprint $table) {

    $table->id();

    $table->foreignId('id_note')
          ->constrained('notes', 'id_note')
          ->cascadeOnDelete();

    $table->foreignId('user_id')
          ->constrained('users');

    $table->string('action');

    $table->text('ancienne_valeur')->nullable();

    $table->text('nouvelle_valeur')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_notes');
    }
};
