<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start_date',
        'data',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array', // Automatically cast the 'data' column to an array
        'week_start_date' => 'date', // Cast to a date object
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}