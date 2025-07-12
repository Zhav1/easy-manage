<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'case_date',
        'patient_name',
        'case_type',
        'details',
        'action_taken',
    ];

    protected $casts = [
        'case_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}