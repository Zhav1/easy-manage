<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvcInfection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'medical_record_number',
        'insertion_date',
        'insertion_location',
        'infection_diagnosis_date',
        'infection_type',
        'clinical_symptoms',
        'microorganism',
        'management',
        'photo_path',
        'status',
    ];

    protected $casts = [
        'insertion_date' => 'date',
        'infection_diagnosis_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}