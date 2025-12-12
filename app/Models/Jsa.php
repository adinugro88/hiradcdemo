<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jsa extends Model
{
    protected $fillable = [
        'project_name',
        'project_id',
        'work_id',
        'job_name',
        'created_date',
        'supervisor_id',
        'site_manager_id',
        'leader_hse_id',
        'project_manager_id',
    ];

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function siteManager()
    {
        return $this->belongsTo(User::class, 'site_manager_id');
    }

    public function leaderHse()
    {
        return $this->belongsTo(User::class, 'leader_hse_id');
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }


    public function steps()
    {
        return $this->hasMany(JsaStep::class)->orderBy('step_number');
    }
}
