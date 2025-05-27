<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'mysql';
    protected $table = 'role';

    public static array $rules = [
        'type' => 'required|string|max: 255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

     public function user(){
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
