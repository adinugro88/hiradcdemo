<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JsaStep extends Model
{
    protected $fillable = [
        'jsa_id', 'step_number', 'work_sequence', 'risk_analysis', 'risk_control', 'pic', 'target_date'
    ];

    public function jsa()
    {
        return $this->belongsTo(Jsa::class);
    }
}