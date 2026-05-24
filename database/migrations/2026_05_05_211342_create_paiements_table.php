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
        Schema::create('paiements', function (Blueprint $table) {
             $table->id('id_paiement');
             $table->foreignId('id_inscription')->unique()->constrained('inscriptions', 'id_inscription')->cascadeOnDelete();
             $table->enum('type_paiement', ['UBA', 'BOA', 'EcoBank']);
             $table->enum('statut_paiement', ['paye', 'non_paye']);
             $table->decimal('montant', 10, 2)->nullable();
             $table->text('recu_chemin')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
