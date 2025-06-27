<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['staff_id', 'shift_id', 'start', 'end'];
    
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
