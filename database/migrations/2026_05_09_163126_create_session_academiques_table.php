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
        Schema::create('sessions_academiques', function (Blueprint $table) {
        $table->id(id_session);
        $table->string('annee'); // ex: 2025-2026
        $table->enum('statut', ['ouverte', 'verrouillee'])->default('ouverte');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_academiques');
    }
};
