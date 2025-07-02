<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'seminar_workshop_webinar',
        'pelatihan',
        'pendidikan_lanjutan',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}   