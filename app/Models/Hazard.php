<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RiskAssessment;
use App\Models\ControlMeasure;
use App\Models\Regulation;
use App\Models\Work;

class Hazard extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'name',
        'risk_description',
        'regulations',
        'control_hierarchy',
    ];

    /**
     * Get the work factor that owns the hazard.
     */
    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get the risk assessment for the hazard.
     */
    public function riskAssessments(): HasMany
    {
        return $this->hasMany(RiskAssessment::class);
    }

    /**
     * Get the control measures for the hazard.
     */
    public function controlMeasures(): HasMany
    {
        return $this->hasMany(ControlMeasure::class);
    }

    /**
     * Get the regulations for the hazard.
     */
    public function regulations(): HasMany
    {
        return $this->hasMany(Regulation::class);
    }
}
