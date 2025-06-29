<?php

// app/Models/PrivateSchedule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrivateSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scheduled_at',
        'briefing',
        'meeting',
        'supervision',
        'handover',
        'external_task',
        'note'
    ];

    protected $casts = [
        'briefing' => 'boolean',
        'meeting' => 'boolean',
        'supervision' => 'boolean',
        'handover' => 'boolean',
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
