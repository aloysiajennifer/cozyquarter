<?php

namespace App\Models;

use App\Http\Controllers\CwspaceController;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $connection = 'mysql';
    protected $fillable = ['status_schedule', 'id_operational_day', 'id_time', 'id_cwspace', 'id_reservation'];
    
    public function time(){
        return $this->belongsTo(Time::class, 'id_time', 'id');
    }

    public function cwspace(){
        return $this->belongsTo(Cwspace::class, 'id_cwspace', 'id');
    }

    public function operationalDay(){
        return $this->belongsTo(OperationalDay::class, 'id_operational_day', 'id');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id');
    }
}
