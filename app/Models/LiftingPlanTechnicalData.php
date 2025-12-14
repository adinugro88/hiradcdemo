<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanTechnicalData extends Model
{
    use HasFactory;

    protected $table = 'lifting_plan_technical_data';

    protected $fillable = [
        'lifting_plan_id',
        'equipment_id',
        'max_equipment_capacity_ton',
        'main_boom_length_m',
        'working_radius_m',
        'lifting_angle_deg',
        'outrigger_condition',
        'lifting_capacity_ton',
        'load_chart_source',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LiftingPlan::class, 'lifting_plan_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(LiftingEquipment::class, 'equipment_id');
    }
}
