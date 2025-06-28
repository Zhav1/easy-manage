<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code'];

    public function hospital()
{
    return $this->belongsTo(Hospital::class);
}

public function users()
{
    return $this->hasMany(User::class);
}

    
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
