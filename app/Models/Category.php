<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mysql';
    protected $table = 'category';

    public $fillable = [
        'name_category'
    ];

    public static array $rules = [
        'name_category' => 'required|string',
    ];

    public function book(){
        return $this->HasMany(Book::class, 'id_category', 'id');
    }
}
