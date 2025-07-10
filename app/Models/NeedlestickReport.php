<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedlestickReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'incident_date',
        'incident_time',
        'location',
        'department',
        'injured_person_name',
        'injured_person_position',
        'injured_person_age',
        'injured_person_gender',
        'incident_description',
        'source_patient_status',
        'immediate_actions',
        'follow_up_actions',
        'photo_path',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'immediate_actions' => 'array', // Cast to array for easier handling
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}