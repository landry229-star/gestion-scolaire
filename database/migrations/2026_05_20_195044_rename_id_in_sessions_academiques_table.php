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
        Schema::table('sessions_academiques', function (Blueprint $table) {
            // Renomme 'id' en 'id_session' proprement
            $table->renameColumn('id', 'id_session');
        });
    }

    public function down(): void
    {
        Schema::table('sessions_academiques', function (Blueprint $table) {
            // Permet de revenir en arrière si nécessaire
            $table->renameColumn('id_session', 'id');
        });
    }
};
