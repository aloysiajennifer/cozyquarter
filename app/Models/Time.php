<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    public function schedule(){
        return $this->hasMany(Schedule::class, 'id_schedule', 'id');
    }
}
