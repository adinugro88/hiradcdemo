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
        Schema::create('jsas', function (Blueprint $table) {
            $table->id();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->string('job_name'); // Nama Pekerjaan (input manual)
        $table->date('created_date');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jsas');
    }
};
