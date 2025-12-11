<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'hazard_id',
        'probability_before',
        'severity_before',
        'total_before',
        'category_before',
        'probability_after',
        'severity_after',
        'total_after',
        'category_after',
    ];

    protected $casts = [
        'probability_before' => 'integer',
        'severity_before' => 'integer',
        'total_before' => 'integer',
        'probability_after' => 'integer',
        'severity_after' => 'integer',
        'total_after' => 'integer',
    ];

    /**
     * Get the hazard that owns the risk assessment.
     */
    public function hazard(): BelongsTo
    {
        return $this->belongsTo(Hazard::class);
    }
}
