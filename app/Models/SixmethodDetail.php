<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SixmethodDetail extends Model
{
    protected $table = 'sixmethod_details';

    protected $fillable = [
        'sixmethod_id',
        'step',
        'description',
    ];

    public function sixmethod(): BelongsTo
    {
        return $this->belongsTo(Sixmethod::class, 'sixmethod_id');
    }
}
