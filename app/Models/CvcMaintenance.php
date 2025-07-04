<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvcMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'medical_record_number',
        'maintenance_date',
        'nurse_name',
        'compliance_percentage',
        'elements_data',
    ];

    protected $casts = [
        'elements_data' => 'array',
        'maintenance_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}