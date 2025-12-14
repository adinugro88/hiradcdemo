<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiftingGear extends Model
{
    use HasFactory;

    protected $table = 'lifting_gears';

    protected $fillable = [
        'gear_code',
        'gear_type',
        'description',
        'size_spec',
        'swl_ton',
        'wll_ton',
        'color_code',
        'manufacturer',
        'serial_number',
        'status',
    ];

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class, 'inspectable_id')
            ->where('inspectable_type', 'gear');
    }

    public function planGears(): HasMany
    {
        return $this->hasMany(LiftingPlanGear::class, 'gear_id');
    }
}
