<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable = [
        'hazard_id',
        'title',
    ];

    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }
}
