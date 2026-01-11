<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterWorkProcess extends Model
{
    protected $fillable = ['master_process_id', 'work_id'];

    public function masterProcess()
    {
        return $this->belongsTo(MasterProcess::class);
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
