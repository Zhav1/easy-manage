<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['staff_id', 'shift_id', 'start', 'end', 'user_id'];

     protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
