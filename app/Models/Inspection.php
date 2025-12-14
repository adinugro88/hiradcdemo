<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspectable_type',
        'inspectable_id',
        'inspection_type',
        'inspection_date',
        'validity_days',
        'valid_until',
        'result',
        'findings',
        'corrective_action',
        'inspector_user_id',
        'inspector_name',
        'inspector_company',
        'certificate_number',
        'certificate_file',
        'next_due_date',
    ];

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_user_id');
    }

    public function scopeForEquipment($query, int $equipmentId)
    {
        return $query->where('inspectable_type', 'equipment')
            ->where('inspectable_id', $equipmentId);
    }

    public function scopeForGear($query, int $gearId)
    {
        return $query->where('inspectable_type', 'gear')
            ->where('inspectable_id', $gearId);
    }
}
