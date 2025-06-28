<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'category',
        'item_name',
        'brand',
        'item_code',
        'maintenance_schedule',
        'calibration_date',
        'calibration_expiry_date',
        'stock',
        'unit_of_measure',
        'status'
    ];

    protected $dates = [
        'calibration_date',
        'calibration_expiry_date',
        'created_at',
        'updated_at'
    ];
}