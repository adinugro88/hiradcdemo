<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'sixmethods_id',
    ];

    /**
     * Get the work processes for the work.
     */
    public function workProcesses(): HasMany
    {
        return $this->hasMany(WorkProcess::class);
    }

    /**
     * Get the hazards for the work.
     */
    public function hazards(): HasMany
    {
        return $this->hasMany(Hazard::class);
    }

    /**
     * Get the project processes through work processes.
     */
    public function projectProcesses()
    {
        return $this->belongsToMany(ProjectProcess::class, 'work_processes');
    }

    public function sixmethod(): BelongsTo
    {
        return $this->belongsTo(Sixmethod::class, 'sixmethods_id');
    }
}
