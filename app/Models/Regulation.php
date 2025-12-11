<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable = [
        'hazard_id',
        'title',
        'reference_number',
        'description',
    ];

    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }
}
