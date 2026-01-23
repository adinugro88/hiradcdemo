<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'process',
        '_risk_control_data',
        'prepared_by',
        'checked_by',
        'approved_by',
        'acknowledged_by',
    ];

    /**
     * Get the project that owns the process.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the work processes for the project process.
     */
    public function workProcesses(): HasMany
    {
        return $this->hasMany(WorkProcess::class);
    }

    /**
     * Get the works through work processes.
     */
    public function works()
    {
        return $this->belongsToMany(Work::class, 'work_processes');
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function controlRisks()
    {
        return $this->hasMany(ControlRisk::class);
    }

    public function regulations()
    {
        return $this->belongsToMany(
            Regulation::class,
            'regulation_process',
            'project_process_id',
            'regulations_id'
        );
    }
}
