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
        Schema::table('jsas', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')
                ->nullable()
                ->change();    // izinkan NULL
        });
    }

    public function down(): void
    {
        Schema::table('jsas', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')
                ->nullable(false)
                ->change();
        });
    }
};
