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
    Schema::table('inscriptions', function (Blueprint $table) {
        $table->unsignedBigInteger('validated_by')->nullable();
        $table->unsignedBigInteger('rejected_by')->nullable();
    });
}

public function down(): void
{
    Schema::table('inscriptions', function (Blueprint $table) {
        $table->dropColumn(['validated_by', 'rejected_by']);
    });
}
    
};
