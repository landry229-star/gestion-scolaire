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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id('id_personnel');
            $table->string('nom', 255)->nullable();
            $table->string('prenom', 255)->nullable();
            $table->text('email')->unique();
            $table->string('password', 255);
            $table->enum('sexe', ['F', 'M']);
            $table->string('post');
            $table->binary('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
