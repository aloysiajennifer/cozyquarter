<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalDay extends Model
{
    protected $fillable = ['date'];

    public function schedule(){
        return $this->hasMany(Schedule::class, 'id_schedule', 'id');;
    }
}
