<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserModel extends Model
{
    //public $timestamps = false;

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

    /*public function addUser(array $request)
    {
        $user = new UserModel;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();
        return $user->toArray();
    }*/

    /*public function login(array $request)
    {
        $user = array();
        $user['name'] = $request['name'];
        $user['password'] = Hash::make($request['password']);
        $user_info = UserModel::where('name', $user['name'])->first();
        return $user_info;
    }*/

}
