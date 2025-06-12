<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'status_reservation',
        'check_in_time',
        'check_out_time',
        'timestamp_reservation',
        'id_user',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function schedule(){
        return $this->hasMany(Schedule::class, 'id_reservation', 'id');
    }
}
