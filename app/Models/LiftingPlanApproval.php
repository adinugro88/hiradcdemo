<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftingPlanApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'lifting_plan_id',
        'user_id',
        'role',
        'signed_at',
        'note',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LiftingPlan::class, 'lifting_plan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
