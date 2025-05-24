<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cwspace extends Model
{
    protected $fillable = ['code_cwspace', 'capacity_cwspace', 'status_cwspace'];
    
    
    public function schedule(){
        return $this->hasMany(Schedule::class, 'id_schedule', 'id');
    }
    
}
