<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanEquipment extends Model
{
    use HasFactory;

    protected $table = 'lifting_plan_equipments';

    protected $fillable = [
        'lifting_plan_id',
        'equipment_id',
        'role',
        'notes',
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
