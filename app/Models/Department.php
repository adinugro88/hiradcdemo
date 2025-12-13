<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // Define relationships and other model methods here

    //relasi user belongs to department
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
