<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'orders';

    protected $fillable = [
        'total_price',
        'status_order',
        'reservation_id',
    ];

    public static array $rules = [
        'total_price' => 'required|integer|gt:0',
        'status_order' => 'required|boolean',
        'reservation_id' => 'required|exists:reservations,id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }
}
