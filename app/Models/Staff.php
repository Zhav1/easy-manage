<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = ['name', 'position_id', 'department_id', 'hospital_id', 'user_id',  'status'];
    protected $table = 'staff';
    
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
