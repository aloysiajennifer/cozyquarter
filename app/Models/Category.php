<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    public function book(){
        return $this->HasMany(Book::class, 'id_category', 'id');
    }
}
