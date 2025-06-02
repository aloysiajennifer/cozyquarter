<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverages extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'beverages';

    protected $fillable = [
        'name',
        'price',
        'image',
        'availability'
    ];

    public static array $rules = [
        'name' => 'required|string|max: 255',
        'price' => 'required|integer|gt:0',
        'image' => 'required|string',
        'availability' => 'required|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

      public function orderdetail()
    {
        return $this->hasMany(OrderDetails::class, 'beverage_id', 'id');
    }
}
