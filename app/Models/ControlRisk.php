<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlRisk extends Model
{
    protected $table = 'control_risk';

    protected $fillable = [
        'project_process_id',
        'control_measures_id',
        'probability',
        'severity',
        'total_value',
        'category',
    ];

    /**
     * Get the project process that owns this control risk.
     */
    public function projectProcess(): BelongsTo
    {
        return $this->belongsTo(ProjectProcess::class);
    }

    /**
     * Get the control measure that owns this control risk.
     */
    public function controlMeasure(): BelongsTo
    {
        return $this->belongsTo(ControlMeasure::class, 'control_measures_id');
    }
}
