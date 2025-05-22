<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverages extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'beverages';

    public $fillable = [
        'name',
        'price',
        'image',
        'availability'
    ];

    public static array $rules = [
        'name' => 'required|string|max: 255',
        'price' => 'required|integer',
        'image' => 'required|string',
        'availability' => 'required|integer|in:0,1',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    //relasi (MASIH BELUM)
}
