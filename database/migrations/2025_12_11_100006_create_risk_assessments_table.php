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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hazard_id')->constrained()->onDelete('cascade');
            $table->integer('probability_before');
            $table->integer('severity_before');
            $table->integer('total_before');
            $table->string('category_before');
            $table->integer('probability_after');
            $table->integer('severity_after');
            $table->integer('total_after');
            $table->string('category_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
