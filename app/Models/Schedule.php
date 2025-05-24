<?php

namespace App\Models;

use App\Http\Controllers\CwspaceController;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function time(){
        return $this->belongsTo(Time::class, 'id_time', 'id');
    }

    public function cwspace(){
        return $this->belongsTo(CwspaceController::class, 'id_cwspace', 'id');
    }
}
