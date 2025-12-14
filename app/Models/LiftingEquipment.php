<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiftingEquipment extends Model
{
    use HasFactory;

    protected $table = 'lifting_equipment';

    protected $fillable = [
        'equipment_code',
        'equipment_name',
        'equipment_type',
        'brand',
        'model',
        'serial_number',
        'year',
        'max_capacity_ton',
        'load_chart_ref',
        'boom_length_min_m',
        'boom_length_max_m',
        'owner_company',
        'status',
    ];

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class, 'inspectable_id')
            ->where('inspectable_type', 'equipment');
    }

    public function planEquipments(): HasMany
    {
        return $this->hasMany(LiftingPlanEquipment::class, 'equipment_id');
    }

    public function technicalData(): HasMany
    {
        return $this->hasMany(LiftingPlanTechnicalData::class, 'equipment_id');
    }
}
