<?php


namespace App\Http\Services\PageService;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserPS
{
    public function login(array $request)
    {
        try {
            $name = $request['name'];
            //$password = Hash::make($request['password']);
            //dd(strlen($password));
            $user_info = UserModel::where('name', $name)->first();
            $result = array();
            if (!$user_info) {
                throw new \Exception('The username does not exist!');
            } elseif (!Hash::check($request['password'], $user_info->password)) {
                throw new \Exception('The password is wrong, please type it!');
            } else {
                $user_in = array('uid' => $user_info->uid, 'username' => $user_info->name);
                session(['user' => $user_in]);
                $result['status'] = 1;
                $result['msg'] = 'login success!';
                $result['data'] = $user_info;
            }
            return $result;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}