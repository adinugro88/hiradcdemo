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
        Schema::create('lifting_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('equipment_code', 100)->unique();
            $table->string('equipment_name');
            $table->enum('equipment_type', [
                'crane',
                'forklift',
                'hoist',
                'chain_block',
                'mobile_crane',
                'tower_crane',
                'other',
            ]);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 150)->nullable();
            $table->integer('year')->nullable();
            $table->decimal('max_capacity_ton', 10, 2)->nullable();
            $table->string('load_chart_ref')->nullable();
            $table->decimal('boom_length_min_m', 10, 2)->nullable();
            $table->decimal('boom_length_max_m', 10, 2)->nullable();
            $table->string('owner_company')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->timestamps();
        });

        Schema::create('lifting_gears', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gear_code', 100)->unique();
            $table->enum('gear_type', [
                'sling_wire',
                'sling_webbing',
                'shackle',
                'hook',
                'spreader_bar',
                'master_link',
                'other',
            ]);
            $table->string('description')->nullable();
            $table->string('size_spec')->nullable();
            $table->decimal('swl_ton', 10, 2)->nullable();
            $table->decimal('wll_ton', 10, 2)->nullable();
            $table->string('color_code', 50)->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('serial_number', 150)->nullable();
            $table->enum('status', ['active', 'inactive', 'discarded', 'maintenance'])->default('active');
            $table->timestamps();
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('inspectable_type', ['equipment', 'gear']);
            $table->unsignedBigInteger('inspectable_id');
            $table->enum('inspection_type', [
                'daily',
                'monthly',
                'quarterly',
                'yearly',
                'load_test',
                'third_party',
                'pre_use',
                'post_repair',
                'other',
            ]);
            $table->date('inspection_date');
            $table->integer('validity_days')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('result', ['pass', 'fail', 'conditional']);
            $table->text('findings')->nullable();
            $table->text('corrective_action')->nullable();
            $table->unsignedBigInteger('inspector_user_id')->nullable();
            $table->string('inspector_name')->nullable();
            $table->string('inspector_company')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('certificate_file')->nullable();
            $table->date('next_due_date')->nullable();
            $table->timestamps();

            $table->index(['inspectable_type', 'inspectable_id'], 'idx_inspections_inspectable');
            $table->index('inspection_date', 'idx_inspections_date');

            $table->foreign('inspector_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::create('lifting_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_number');
            $table->string('revision', 50)->default('0');
            $table->string('form_code', 50)->default('FM/HSE-1/20');
            $table->unsignedBigInteger('project_id');
            $table->string('location')->nullable();
            $table->date('plan_date');
            $table->string('material_type')->nullable();
            $table->decimal('maximum_load_ton', 10, 2)->nullable();
            $table->string('crane_type')->nullable();
            $table->enum('lifting_type', ['critical', 'complex', 'routine']);
            $table->text('communication_method')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'closed'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('project_process_id')->nullable();
            $table->unsignedBigInteger('jsa_id')->nullable();
            $table->timestamps();

            $table->index('project_id', 'idx_lifting_plans_project');

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('project_process_id')
                ->references('id')
                ->on('project_processes')
                ->nullOnDelete();

            $table->foreign('jsa_id')
                ->references('id')
                ->on('jsas')
                ->nullOnDelete();
        });

        Schema::create('lifting_plan_equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->unsignedBigInteger('equipment_id');
            $table->enum('role', ['main', 'tailing', 'support', 'other'])->default('main');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index('lifting_plan_id', 'idx_lpe_plan');
            $table->index('equipment_id', 'idx_lpe_equipment');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();

            $table->foreign('equipment_id')
                ->references('id')
                ->on('lifting_equipment')
                ->restrictOnDelete();
        });

        Schema::create('lifting_plan_loads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->decimal('weight_material_ton', 10, 2)->nullable();
            $table->decimal('weight_shackle_ton', 10, 2)->nullable();
            $table->decimal('weight_hook_ton', 10, 2)->nullable();
            $table->decimal('weight_sling_ton', 10, 2)->nullable();
            $table->decimal('total_weight_ton', 10, 2)->nullable();
            $table->timestamps();

            $table->unique('lifting_plan_id', 'uniq_load_plan');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();
        });

        Schema::create('lifting_plan_technical_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->decimal('max_equipment_capacity_ton', 10, 2)->nullable();
            $table->decimal('main_boom_length_m', 10, 2)->nullable();
            $table->decimal('working_radius_m', 10, 2)->nullable();
            $table->decimal('lifting_angle_deg', 10, 2)->nullable();
            $table->enum('outrigger_condition', ['full', 'partial', 'not_applicable'])->default('not_applicable');
            $table->decimal('lifting_capacity_ton', 10, 2)->nullable();
            $table->string('load_chart_source')->nullable();
            $table->timestamps();

            $table->index('lifting_plan_id', 'idx_lptd_plan');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();

            $table->foreign('equipment_id')
                ->references('id')
                ->on('lifting_equipment')
                ->nullOnDelete();
        });

        Schema::create('lifting_plan_gears', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->unsignedBigInteger('gear_id');
            $table->integer('used_quantity')->default(1);
            $table->string('size_used')->nullable();
            $table->decimal('swl_used_ton', 10, 2)->nullable();
            $table->timestamps();

            $table->index('lifting_plan_id', 'idx_lpg_plan');
            $table->index('gear_id', 'idx_lpg_gear');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();

            $table->foreign('gear_id')
                ->references('id')
                ->on('lifting_gears')
                ->restrictOnDelete();
        });

        Schema::create('lifting_plan_safety', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->decimal('total_load_ton', 10, 2)->nullable();
            $table->decimal('lifting_capacity_ton', 10, 2)->nullable();
            $table->decimal('safety_factor', 10, 4)->nullable();
            $table->enum('safety_status', ['safe', 'unsafe', 'unknown'])->default('unknown');
            $table->string('rule_note')->default('Safe when SF > 1.2');
            $table->timestamps();

            $table->unique('lifting_plan_id', 'uniq_safety_plan');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();
        });

        Schema::create('lifting_plan_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lifting_plan_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['dibuat', 'diperiksa', 'disetujui', 'diketahui']);
            $table->timestamp('signed_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index('lifting_plan_id', 'idx_lpa_plan');
            $table->index('user_id', 'idx_lpa_user');

            $table->foreign('lifting_plan_id')
                ->references('id')
                ->on('lifting_plans')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifting_plan_approvals');
        Schema::dropIfExists('lifting_plan_safety');
        Schema::dropIfExists('lifting_plan_gears');
        Schema::dropIfExists('lifting_plan_technical_data');
        Schema::dropIfExists('lifting_plan_loads');
        Schema::dropIfExists('lifting_plan_equipments');
        Schema::dropIfExists('lifting_plans');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('lifting_gears');
        Schema::dropIfExists('lifting_equipment');
    }
};
