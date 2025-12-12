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
            $table->dropColumn('opportunity_measure');
            $table->foreignId('opportunity_id')->nullable()->after('basic_measure')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_measures', function (Blueprint $table) {
            $table->dropForeign(['opportunity_id']);
            $table->dropColumn('opportunity_id');
            $table->text('opportunity_measure')->after('basic_measure');
        });
    }
};
