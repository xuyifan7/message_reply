<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\MessageModel;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function create(MessageCreateRequest $request)
    {
        $data = $request->validated();
        //$user = $request->user();
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageModel::class)->message_create($data);
        return response()->json(['status' => 1, 'msg' => 'create message success！', 'data' => $result]);
    }

    public function update(MessageUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageModel::class)->message_update($data, $id);
        return response()->json(['status' => 1, 'msg' => 'update message success！', 'data' => $result]);
    }

    public function delete($id)
    {
        $result = app(MessageModel::class)->message_delete($id);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

    public function list()
    {
        $result = app(MessageModel::class)->show_list();
        return response()->json(['status' => 1, 'msg' => 'Messages list', 'data' => $result]);
    }

    public function info(MessageInfoRequest $request)
    {
        $data = $request->validated();
        $result = app(MessageModel::class)->show_info($data);
        return response()->json(['status' => 0, 'msg' => 'messages of one user', 'data' => $result]);
    }
}