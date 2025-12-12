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
        Schema::table('control_measures', function (Blueprint $table) {
            $table->dropColumn('control_hierarchy');
            $table->text('opportunity_measure')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_measures', function (Blueprint $table) {
            $table->text('control_hierarchy');
        });
    }
};
