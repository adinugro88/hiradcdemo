<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanSafety extends Model
{
    use HasFactory;

    protected $table = 'lifting_plan_safety';

    protected $fillable = [
        'lifting_plan_id',
        'total_load_ton',
        'lifting_capacity_ton',
        'safety_factor',
        'safety_status',
        'rule_note',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LiftingPlan::class, 'lifting_plan_id');
    }
}
