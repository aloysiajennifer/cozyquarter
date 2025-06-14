<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'username',
        'password',
        'penalty_counter', // <--- Keep this
        // 'is_blacklisted', // <--- REMOVE THIS LINE if it's there from previous iterations
        'role_id',
    ];

    public static array $rules = [
        'name' => 'required|string',
        'phone' => 'required|string',
        'email' => 'required|string',
        'username' => 'required|string',
        'password' => 'required|string',
        'role_id' => 'required',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'role_id',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // 'is_blacklisted' => 'boolean', // <--- REMOVE THIS LINE if it's there
            'penalty_counter' => 'integer', // <--- Keep this
        ];
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function borrowing(){
        return $this->hasMany(Borrowing::class, 'id_user', 'id');
    }

    public function reservation(){
        // Corrected: A user has many reservations, foreign key is 'id_user' on the reservations table.
        return $this->hasMany(Reservation::class, 'id_user', 'id');
    }

    /**
     * Helper method to check if the user is blacklisted based on penalty_counter.
     */
    public function isBlacklisted(): bool
    {
        return $this->penalty_counter >= 3; // <--- UPDATED LOGIC HERE
    }
}