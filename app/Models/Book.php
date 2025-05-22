<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'book';

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
