<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanGear extends Model
{
    use HasFactory;

    protected $table = 'lifting_plan_gears';

    protected $fillable = [
        'lifting_plan_id',
        'gear_id',
        'used_quantity',
        'size_used',
        'swl_used_ton',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LiftingPlan::class, 'lifting_plan_id');
    }

    public function gear(): BelongsTo
    {
        return $this->belongsTo(LiftingGear::class, 'gear_id');
    }
}
