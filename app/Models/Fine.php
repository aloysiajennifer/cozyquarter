<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $table = 'fine';

    public function borrowing(){
        return $this->belongsTo(Borrowing::class, 'id_borrowing', 'id');
    }
}
