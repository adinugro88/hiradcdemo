<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    protected $fillable = [
        'project_process_id',
        'risk_assessment_id',
        'probability',
        'severity',
        'total_value',
        'category',
    ];

    public function projectProcess(): BelongsTo
    {
        return $this->belongsTo(ProjectProcess::class);
    }

    /**
     * Get the risk assessment that owns this risk.
     */
    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class);
    }
}
