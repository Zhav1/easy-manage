<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model {
    protected $fillable = ['name'];

    public function users() {
        return $this->hasMany(User::class);
    }
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    

}
