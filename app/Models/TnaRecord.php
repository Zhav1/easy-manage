<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TnaRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'type', 'title', 'description', 
        'start_date', 'end_date', 'organizer', 'certificate'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
