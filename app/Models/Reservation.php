<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    // These fillable fields now include all the new booking details.
    // This is the correct way to allow mass assignment for these fields.
    protected $fillable = [
        'status_reservation',        // Stores NULL, 0, 1, 2, 3
        'reservation_date',          // The date of the booking (DATE)
        'reservation_start_time',    // Start time of the booking (TIME)
        'reservation_end_time',      // End time of the booking (TIME)
        'reservation_code_cwspace',  // Code of the booked room (STRING)
        'check_in_time',             // Actual check-in timestamp (DATETIME, set by friend's admin logic)
        'check_out_time',            // Actual check-out timestamp (DATETIME, set by friend's admin logic)
        'timestamp_reservation',     // When the reservation was initially created (TIMESTAMP)
        'id_user',                   // Foreign key to users table
        'name',                      // User's name from the booking form
        'purpose',                   // Purpose of the reservation
        'email',                     // User's contact (email) - CHANGED FROM contact_number
        'num_participants',          // Number of participants
    ];

    // Adopt the status integers that align with your friend's ReservationController's database usage.
    public const STATUS_ATTENDED = 0;      // Maps to friend's '0: Attended'
    public const STATUS_NOT_ATTENDED = 1;  // Maps to friend's '1: Not Attended'
    public const STATUS_CANCELLED = 2;     // Maps to friend's '2: Cancelled'
    public const STATUS_CLOSED = 3;        // Maps to friend's '3: Closed'

    // Explicitly cast these attributes for proper handling by Eloquent.
    // Ensure these match your database column types.
    protected $casts = [
        'reservation_date' => 'date',           // DATE type in DB
        'reservation_start_time' => 'string',   // TIME type in DB, store as string HH:MM:SS
        'reservation_end_time' => 'string',     // TIME type in DB, store as string HH:MM:SS
        'check_in_time' => 'datetime',          // DATETIME/TIMESTAMP type in DB
        'check_out_time' => 'datetime',         // DATETIME/TIMESTAMP type in DB - Added cast for completeness
        'timestamp_reservation' => 'datetime',  // DATETIME/TIMESTAMP type in DB
        // 'email' does not typically need casting if it's a VARCHAR in DB
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_reservation', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'id_reservation', 'id');
    }
}