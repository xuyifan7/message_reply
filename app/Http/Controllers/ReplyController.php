<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Http\Services\DataService\ReplyDS;
use App\Models\ReplyModel;
use http\Env\Response;

class ReplyController extends Controller
{
    public function replyCreate(ReplyCreateRequest $request)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyDS::class)->replyCreate($data);
        return response()->json($result);
    }

    public function replyUpdate(ReplyUpdateRequest $request, $rid)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(ReplyDS::class)->replyUpdate($data, $rid);
        return response()->json($result);
    }

    public function replyDelete($rid)
    {
        $result = app(ReplyDS::class)->replyDelete($rid);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

}
