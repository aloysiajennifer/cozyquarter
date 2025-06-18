<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'status_reservation',
        'reservation_date',
        'reservation_start_time',
        'reservation_end_time',
        'reservation_code_cwspace',
        'check_in_time',
        'check_out_time',
        'timestamp_reservation',
        'id_user',
        'name',
        'purpose',
        'email',
        'num_participants',
    ];

    // UPDATED: Constants aligned with your requested flow (0=Reserved, 1=Attended, etc.)
    public const STATUS_RESERVED = 0;       // Initial state when user books
    public const STATUS_ATTENDED = 1;       // Admin/User marks as attended
    public const STATUS_NOT_ATTENDED = 2;   // System auto-sets if reserved and time passed without check-in
    public const STATUS_CANCELLED = 3;      // User cancels
    public const STATUS_CLOSED = 4;         // System auto-sets for all past bookings (except cancelled)

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_start_time' => 'string',
        'reservation_end_time' => 'string',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'timestamp_reservation' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'id_reservation', 'id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'reservation_id', 'id');
    }
}