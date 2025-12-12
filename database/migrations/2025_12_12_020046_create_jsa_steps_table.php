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
        Schema::create('jsa_steps', function (Blueprint $table) {
            $table->id();
        $table->foreignId('jsa_id')->constrained()->onDelete('cascade');
        $table->integer('step_number');
        $table->text('work_sequence'); // Urutan Pekerjaan
        $table->text('risk_analysis'); // Analisa Risiko
        $table->text('risk_control'); // Pengendalian Risiko
        $table->string('pic')->nullable();
        $table->date('target_date')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jsa_steps');
    }
};
