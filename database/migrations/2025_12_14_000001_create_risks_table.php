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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_process_id')->constrained()->onDelete('cascade');
            $table->foreignId('risk_assessment_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('probability');
            $table->unsignedTinyInteger('severity');
            $table->unsignedTinyInteger('total_value');
            $table->string('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
