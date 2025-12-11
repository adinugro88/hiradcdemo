<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_process_id',
        'work_id',
    ];

    /**
     * Get the project process that owns the work process.
     */
    public function projectProcess(): BelongsTo
    {
        return $this->belongsTo(ProjectProcess::class);
    }

    /**
     * Get the work that owns the work process.
     */
    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}
