<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Staff;

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

    protected function overallScore(): Attribute
    {
        return Attribute::make(
            get: fn () => round(
                (
                    $this->kedisiplinan +
                    $this->komunikasi +
                    $this->komplain +
                    $this->kepatuhan +
                    $this->target_kerja
                ) / 5
            ),
        );
    }

    /**
     * Get the staff member that owns the performance evaluation.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}