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
        Schema::table('users', function (Blueprint $table) {
             $table->foreignId('department_id')
                ->nullable()
                ->constrained()   // refer ke tabel "departments" kolom "id"
                ->nullOnDelete(); // atau ->cascadeOnDelete() sesuai kebutuhan

            $table->foreignId('position_id')
                ->nullable()
                ->constrained()   // refer ke tabel "positions" kolom "id"
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['department_id', 'position_id']);
        });
    }
};
