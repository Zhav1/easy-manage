<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'logistic_id',
        'quantity',
        'notes',
        'user_id'
    ];

    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}