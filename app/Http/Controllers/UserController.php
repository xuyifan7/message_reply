<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\PageService\UserPS;
use App\Http\Services\DataService\UserDS;

class UserController extends Controller
{

    public function postRegister(RegisterRequest $request)
    {
        $data = $request->validated();
        $result = app(UserDS::class)->addUser($data);
        return response()->json(['status' => 1, 'msg' => 'register success！', 'data' => $result]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = app(UserPS::class)->login($data);
        return response()->json($result);
    }

    public function logout()
    {
        session()->forget('user');
        return response()->json(['status' => 1, 'msg' => 'logout success！']);
    }

}
