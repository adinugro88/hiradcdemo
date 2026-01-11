<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterProcess extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all master work processes for this master process.
     */
    public function masterWorkProcesses(): HasMany
    {
        return $this->hasMany(MasterWorkProcess::class);
    }

    /**
     * Get all works through master work processes.
     */
    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'master_work_processes', 'master_process_id', 'work_id');
    }
}
