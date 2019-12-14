<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function postRegister(RegisterRequest $request)
    {
        $data = $request->validated();
        $result = app(UserModel::class)->addUser($data);
        return response()->json(['status' => 1, 'msg' => 'register success！', 'data' => $result]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = app(UserModel::class)->login($data);
        if (!$result) {
            return response()->json(['msg' => '用户名不存在!']);
        }
        if (!Hash::check($data['password'], $result->password)) {
            return response()->json(['msg' => '密码有误，请重新输入!']);
        } else {
            $user_in = array('uid' => $result->uid, 'username' => $result->name);
            //$request->session()->put('user', $user_in);
            session(['user' => $user_in]);
            return response()->json(['status' => 1, 'msg' => 'login success！', 'data' => $result]);
        }
    }

    public function logout(Request $request)
    {
        //app(UserModel::class)->logout($request);
        if ($request->session()->has('user')) {
            $request->session()->forget('user');
        }
        return response()->json(['status' => 1, 'msg' => 'logout success！']);
    }

}
