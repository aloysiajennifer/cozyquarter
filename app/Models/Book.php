<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $connection = 'mysql';
    protected $table = 'book';

    public $fillable = [
        'title_book',
        'author_book',
        'isbn_book',
        'synopsis_book',
        'cover_book',
        'status_book'
    ];


    public function category(){
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function shelf(){
        return $this->belongsTo(Shelf::class, 'id_shelf', 'id');
    }

    public function borrowing(){
        return $this->hasMany(Borrowing::class, 'id_book', 'id');
    }
}
