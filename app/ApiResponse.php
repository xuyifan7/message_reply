<?php

namespace App;

use Illuminate\Support\Facades\Response;

define('RESPONSE_SUCCESS_CODE', 1);

class ApiResponse
{
    public function success(array $data = [], string $msg ='', int $code = RESPONSE_SUCCESS_CODE)
    {
        return $this->json($data, $msg, $code);
    }

    public function error(array $data = [], string $msg ='', int $code = -1)
    {
        return $this->json($data, $msg, $code);
    }

    public function empty(array $data = [], string $msg ='', int $code = 0)
    {
        return $this->json($data, $msg, $code);
    }

    public function json(array $data, string $msg, int $code)
    {
        return Response::json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }
}