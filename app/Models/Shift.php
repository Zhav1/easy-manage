<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['code', 'start', 'end'];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
