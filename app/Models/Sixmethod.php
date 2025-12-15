<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sixmethod extends Model
{
    protected $table = 'sixmethods';

    protected $fillable = [
        'name',
        'description',
    ];
}
