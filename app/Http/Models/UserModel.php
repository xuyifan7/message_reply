<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserModel extends Model
{
    protected $table = 'user';

    public $primaryKey = 'uid';
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'email'
    ];

}
