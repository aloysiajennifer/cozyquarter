<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $connection = 'mysql';
    protected $table = 'shelf';

    public $fillable = [
        'code_shelf'
    ];

    public static array $rules = [
        'code_shelf' => 'required|string',
    ];


    public function book(){
        return $this->HasMany(Book::class, 'id_shelf', 'id');
    }
}
