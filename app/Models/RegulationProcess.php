<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulationProcess extends Model
{
    protected $table = 'regulation_process';

    protected $fillable = [
        'project_process_id',
        'regulations_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project process that owns this regulation process.
     */
    public function projectProcess(): BelongsTo
    {
        return $this->belongsTo(ProjectProcess::class);
    }

    /**
     * Get the regulation that owns this regulation process.
     */
    public function regulation(): BelongsTo
    {
        return $this->belongsTo(Regulation::class, 'regulations_id');
    }
}
