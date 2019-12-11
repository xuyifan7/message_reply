<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Reply;

class ReplyController extends Controller
{
    public function reply_create(ReplyCreateRequest $request){
        $data = $request->validated();
        $result = app(Reply::class)->reply_create($data);
        return response()->json(['status' => 1, 'msg' => 'create reply success！', 'data' => $result]);
    }

    public function reply_update(ReplyUpdateRequest $request, $rid){
        $data = $request->validated();
        $result = app(Reply::class)->reply_update($data, $rid);
        return response()->json(['status' => 1, 'msg' => 'update reply success！', 'data' => $result]);
    }

    public function reply_delete($rid){
        $result = app(Reply::class)->reply_delete($rid);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

}
