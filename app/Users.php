<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'user';

    public $primaryKey = 'uid';
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
  /*  protected $fillable = [
        'name','password','email'
    ];*/

    private $name;
    private $password;
    private $email;
}
