<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = ['start_time', 'end_time'];
    public function schedule(){
        return $this->hasMany(Schedule::class, 'id_schedule', 'id');
    }
}
