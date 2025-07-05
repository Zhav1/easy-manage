<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_time',
        'briefing_conducted',
        'meeting_held',
        'supervision_conducted',
        'handover_done',
        'external_task_performed',
        'report_status',
        'notes',
    ];

    protected $casts = [
        'log_time' => 'datetime',
        'briefing_conducted' => 'boolean',
        'meeting_held' => 'boolean',
        'supervision_conducted' => 'boolean',
        'handover_done' => 'boolean',
        'external_task_performed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}