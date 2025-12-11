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
        Schema::create('control_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hazard_id')->constrained()->onDelete('cascade');
            $table->text('basic_measure');
            $table->text('opportunity_measure');
            $table->text('advanced_measure')->nullable();
            $table->text('control_hierarchy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_measures');
    }
};
