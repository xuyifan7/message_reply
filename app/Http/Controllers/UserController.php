<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use App\Users;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function postRegister(RegisterRequest $request){
        $data = $request->validated();
        $result = app(Users::class)->register($data);
        /*$user = new Users;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();
        var_dump($user->toArray());*/
        return response()->json(['status'=>1,'msg'=>'register success！','data'=>$result]);
    }

    public function login(LoginRequest $request){
        $data = $request->validated();
        $result = app(Users::class)->login($data);
        /*$user = array();
        $user['name'] = $data['name'];
        $user['password'] = Hash::make($data['password']);
        $user_info = Users::where('name',$user['name'])->first();*/
        if (!$result){
            return response()->json(['msg'=>'用户名不存在!']);
        }
        if (!Hash::check($result->password,Hash::make($data['password']))){
            return response()->json(['msg'=>'密码有误，请重新输入!']);
        }
        else{
            $user_in = array('uid'=>$result->uid,'username'=>$result->name);
            $request->session()->put('user',$user_in);
            return response()->json(['status'=>1,'msg'=>'login success！','data'=>$result]);
        }
    }

    public function logout(Request $request){
        app(Users::class)->logout($request);
        /*if ($request->session()->has('user')){
            $request->session()->forget('user');
        }*/
        return response()->json(['status'=>1,'msg'=>'logout success！']);
    }

}
