<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'kedisiplinan',
        'komunikasi',
        'komplain',
        'kepatuhan',
        'target_kerja',
        'status_kinerja',
        'notes',
    ];

    /**
     * Get the staff member that owns the performance evaluation.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}