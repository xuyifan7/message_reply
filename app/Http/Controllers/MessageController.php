<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\MessageModel;
use App\ReplyModel;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function create(MessageCreateRequest $request)
    {
        $data = $request->validated();
        //$user = $request->user();
        //$user = session()->get('user');
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageModel::class)->messageCreate($data);
        return response()->json(['status' => 1, 'msg' => 'create message success！', 'data' => $result]);
    }

    public function update(MessageUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageModel::class)->messageUpdate($data, $id);
        return response()->json(['status' => 1, 'msg' => 'update message success！', 'data' => $result]);
    }

    public function delete($id)
    {
        $result = app(MessageModel::class)->messageDelete($id);
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

    public function list()
    {
        $result = app(MessageModel::class)->showList();
        return response()->json(['status' => 1, 'msg' => 'Messages list', 'data' => $result]);
    }

    public function info(MessageInfoRequest $request)
    {
        $data = $request->validated();
        $result = app(MessageModel::class)->showInfo($data);
        return response()->json(['status' => 1, 'msg' => 'messages of one user', 'data' => $result]);
    }

    public function replyList($data, $reply_id)
    {
        $re = array();
        foreach ($data as $k => $v) {
            //$v['reply_id'] = &$data;
            if (empty($data)) {
                return;
            }
            if ($v['reply_id'] == $reply_id) {
                $re[$k] = $v;
                unset($data[$k]);
                $re[$k]['replies'] = $this->replyList($data, $v['rid']);
            }
        }
        return $re;
    }


}