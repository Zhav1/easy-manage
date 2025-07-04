<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvcInsertion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'medical_record_number',
        'insertion_date',
        'insertion_location',
        'operator_name',
        'compliance_percentage',
        'elements_data',
    ];

    protected $casts = [
        'elements_data' => 'array',
        'insertion_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}