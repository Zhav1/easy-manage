<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumableItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'unit_of_measure',
        'minimum_stock'
    ];

    protected $dates = ['deleted_at'];
}