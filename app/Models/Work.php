<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
    ];

    /**
     * Get the work processes for the work.
     */
    public function workProcesses(): HasMany
    {
        return $this->hasMany(WorkProcess::class);
    }

    /**
     * Get the project processes through work processes.
     */
    public function projectProcesses()
    {
        return $this->belongsToMany(ProjectProcess::class, 'work_processes');
    }
}
