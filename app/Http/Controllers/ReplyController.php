<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\ReplyModel;

class ReplyController extends Controller
{
    public function reply_create(ReplyCreateRequest $request)
    {
        $data = $request->validated();
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyModel::class)->reply_create($data);
        return response()->json(['status' => 1, 'msg' => 'create reply success！', 'data' => $result]);
    }

    public function reply_update(ReplyUpdateRequest $request, $rid)
    {
        $data = $request->validated();
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyModel::class)->reply_update($data, $rid);
        return response()->json(['status' => 1, 'msg' => 'update reply success！', 'data' => $result]);
    }

    public function reply_delete($rid)
    {
        $result = app(ReplyModel::class)->reply_delete($rid);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

}
