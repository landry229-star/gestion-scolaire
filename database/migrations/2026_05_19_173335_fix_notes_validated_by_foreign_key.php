<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // 1. Rendre nullable d'abord (un brouillon n'a pas encore de validateur)
            $table->unsignedBigInteger('validated_by')->nullable()->change();

            // 2. Supprimer l'ancienne FK qui pointe vers "users"
            $table->dropForeign(['validated_by']);

            // 3. Recréer la FK vers "utilisateurs"
            $table->foreign('validated_by')
                  ->references('id_utilisateur')
                  ->on('utilisateurs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);

            $table->foreign('validated_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }
};
