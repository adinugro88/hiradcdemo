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
            // Tambahkan hanya jika belum ada
            if (!Schema::hasColumn('jsas', 'project_name')) {
                $table->string('project_name')->after('id');
            }

            if (!Schema::hasColumn('jsas', 'work_id')) {
                $table->foreignId('work_id')
                    ->nullable()
                    ->references('id')
                    ->on('project_processes')
                    ->nullOnDelete()
                    ->after('project_name');
            }

            if (!Schema::hasColumn('jsas', 'supervisor_id')) {
                $table->foreignId('supervisor_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('jsas', 'site_manager_id')) {
                $table->foreignId('site_manager_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('jsas', 'leader_hse_id')) {
                $table->foreignId('leader_hse_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('jsas', 'project_manager_id')) {
                $table->foreignId('project_manager_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jsas', function (Blueprint $table) {
            //
        });
    }
};
