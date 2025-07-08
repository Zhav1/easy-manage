<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'hospital_id',
        'department_id',
        'id_pegawai',
        'email',
        'password',
        'position', 
        'profile_photo_path'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staff() 
    {
        return $this->hasMany(Staff::class, 'user_id');
    }

    public function privateSchedules()
    {
        return $this->hasMany(PrivateSchedule::class);
    }

    public function getFullPositionAttribute()
    {
        return $this->position . (($this->department) ? ' ' . $this->department->name : '');
    }
}