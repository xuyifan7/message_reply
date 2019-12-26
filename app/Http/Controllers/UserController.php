<?php

namespace App\Http\Controllers;

use App\ApiResponse;
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
        return $result ? app(ApiResponse::class)->success($result, 'register success！') : app(ApiResponse::class)->error([]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = app(UserPS::class)->login($data);
        return app(ApiResponse::class)->success($result, 'login success！');
    }

    public function logout()
    {
        session()->flush();
        return app(ApiResponse::class)->success([], 'logout success！');
    }

}
