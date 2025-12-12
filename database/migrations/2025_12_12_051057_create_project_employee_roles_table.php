<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_employee_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->enum('role', [
                'dibuat',
                'diperiksa',
                'disetujui',
                'diketahui',
            ]);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_employee_roles');
    }
};
