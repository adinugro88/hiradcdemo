<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LiftingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_number',
        'revision',
        'form_code',
        'project_id',
        'location',
        'plan_date',
        'material_type',
        'maximum_load_ton',
        'crane_type',
        'lifting_type',
        'communication_method',
        'status',
        'created_by',
        'project_process_id',
        'jsa_id',
    ];

    protected $casts = [
        'plan_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function projectProcess(): BelongsTo
    {
        return $this->belongsTo(ProjectProcess::class);
    }

    public function jsa(): BelongsTo
    {
        return $this->belongsTo(Jsa::class);
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(LiftingPlanEquipment::class);
    }

    public function gears(): HasMany
    {
        return $this->hasMany(LiftingPlanGear::class);
    }

    public function planLoad(): HasOne
    {
        return $this->hasOne(LiftingPlanLoad::class);
    }

    public function technicalData(): HasMany
    {
        return $this->hasMany(LiftingPlanTechnicalData::class);
    }

    public function safety(): HasOne
    {
        return $this->hasOne(LiftingPlanSafety::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(LiftingPlanApproval::class);
    }
}
