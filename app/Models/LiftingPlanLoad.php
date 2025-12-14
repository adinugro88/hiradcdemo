<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanLoad extends Model
{
    use HasFactory;

    protected $fillable = [
        'lifting_plan_id',
        'weight_material_ton',
        'weight_shackle_ton',
        'weight_hook_ton',
        'weight_sling_ton',
        'total_weight_ton',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LiftingPlan::class, 'lifting_plan_id');
    }
}
