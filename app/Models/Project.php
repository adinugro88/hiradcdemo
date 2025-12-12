<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'department',
        'name',
        'document_number',
        'form_code',
        'revision',
        'page_info',
    ];

    /**
     * Get the project processes for the project.
     */
    public function projectProcesses(): HasMany
    {
        return $this->hasMany(ProjectProcess::class);
    }

    /**
     * Get the employee roles attached to the project.
     */
    public function projectEmployeeRoles(): HasMany
    {
        return $this->hasMany(ProjectEmployeeRole::class);
    }
}
