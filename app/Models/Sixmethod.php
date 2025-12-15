<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sixmethod extends Model
{
    protected $table = 'sixmethods';

    protected $fillable = [
        'name',
        'description',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(SixmethodDetail::class, 'sixmethod_id');
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class, 'sixmethods_id');
    }
}
