<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlMeasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'hazard_id',
        'basic_measure',
        'opportunity_measure',
        'advanced_measure',
        'control_hierarchy',
    ];

    /**
     * Get the hazard that owns the control measure.
     */
    public function hazard(): BelongsTo
    {
        return $this->belongsTo(Hazard::class);
    }
}
