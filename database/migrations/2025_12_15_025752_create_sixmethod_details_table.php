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
        Schema::create('sixmethod_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sixmethod_id')->constrained('sixmethods')->onDelete('cascade');
            $table->string('step');
            $table->text('description')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sixmethod_details');
    }
};
