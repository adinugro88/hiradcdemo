<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jsa extends Model
{
    protected $fillable = ['project_id', 'job_name', 'created_date'];

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    public function steps()
    {
        return $this->hasMany(JsaStep::class)->orderBy('step_number');
    }
}