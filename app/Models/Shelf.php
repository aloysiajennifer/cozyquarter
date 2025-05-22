<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $table = 'shelf';

    public function book(){
        return $this->HasMany(Book::class, 'id_shelf', 'id');
    }
}
