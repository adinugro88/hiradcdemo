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
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->integer('probability_before')->nullable()->change();
            $table->integer('severity_before')->nullable()->change();
            $table->integer('total_before')->nullable()->change();
            $table->string('category_before')->nullable()->change();
            $table->integer('probability_after')->nullable()->change();
            $table->integer('severity_after')->nullable()->change();
            $table->integer('total_after')->nullable()->change();
            $table->string('category_after')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->integer('probability_before')->nullable(false)->change();
            $table->integer('severity_before')->nullable(false)->change();
            $table->integer('total_before')->nullable(false)->change();
            $table->string('category_before')->nullable(false)->change();
            $table->integer('probability_after')->nullable(false)->change();
            $table->integer('severity_after')->nullable(false)->change();
            $table->integer('total_after')->nullable(false)->change();
            $table->string('category_after')->nullable(false)->change();
        });
    }
};
