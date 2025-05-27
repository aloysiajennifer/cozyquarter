<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $table = 'borrowing';

    public function book(){
        return $this->belongsTo(Book::class, 'id_book', 'id');
    }
    public function fine(){
        return $this->hasOne(Fine::class, 'id_borrowing', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
