<?php


namespace App\Http\Services\PageService;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\BaseExceptions;

class UserPS
{
    public function login(array $request)
    {
        $name = $request['name'];
        //$password = Hash::make($request['password']);dd(strlen($password));
        $user_info = UserModel::where('name', $name)->first();
        if (!$user_info) {
            throw new BaseExceptions('The username does not exist!', -1001);
        } elseif (!Hash::check($request['password'], $user_info->password)) {
            throw new BaseExceptions('The password is wrong, please type it!', -1002);
        } else {
            $user_in = array('uid' => $user_info->uid, 'username' => $user_info->name);
            session(['user' => $user_in]);
        }
        return $user_info->toArray();
    }
}