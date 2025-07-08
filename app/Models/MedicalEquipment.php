<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalEquipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'manufacturer',
        'model_number',
        'serial_number'
    ];

    protected $dates = ['deleted_at'];
}