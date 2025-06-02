<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetails extends Model
{
     use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'orderdetails';

    protected $fillable = [
        'quantity',
        'subtotal',
        'order_id',
        'beverage_id',
    ];

    public static array $rules = [
        'quantity' => 'required|integer|gt:0',
        'subtotal' => 'required|integer|gt:0',
        'order_id' => 'required|exists:orders,id',
        'beverage_id' => 'required|exists:beverages,id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function beverage()
    {
        return $this->belongsTo(Beverages::class, 'beverage_id', 'id');
    }
}
