<?php


namespace App\Http\Services\PageService;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserPS
{
    public function login(array $request)
    {
        $user = array();
        $user['name'] = $request['name'];
        $user['password'] = Hash::make($request['password']);
        $user_info = UserModel::where('name', $user['name'])->first();
        $result = array();
        $result['status'] = 0;
        if (!$user_info) {
            $result['msg'] = "用户名不存在!";
        }
        if (!Hash::check($request['password'], $user_info->password)) {
            $result['msg'] = "密码有误，请重新输入!";
        } else {
            $user_in = array('uid' => $user_info->uid, 'username' => $user_info->name);
            //$request->session()->put('user', $user_in);
            session(['user' => $user_in]);
            $result['status'] = 1;
            $result['msg'] = "login success！";
            $result['data'] = $user_info;
        }
        return $result;
    }

    public function logout($request)
    {
        if ($request->session()->has('user')) {
            $request->session()->forget('user');
        }
    }
}