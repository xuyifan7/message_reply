<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\ReplyModel;

class ReplyController extends Controller
{
    public function replyCreate(ReplyCreateRequest $request)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyModel::class)->replyCreate($data);
        return response()->json(['status' => 1, 'msg' => 'create reply success！', 'data' => $result]);
    }

    public function replyUpdate(ReplyUpdateRequest $request, $rid)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyModel::class)->replyUpdate($data, $rid);
        return response()->json(['status' => 1, 'msg' => 'update reply success！', 'data' => $result]);
    }

    public function replyDelete($rid)
    {
        $result = app(ReplyModel::class)->replyDelete($rid);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

}
