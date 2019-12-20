<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageListRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\OpenAllInfoRequest;
use App\Http\Services\DataService\MessageDS;
use App\Http\Services\PageService\MessagePS;
use App\Models\MessageModel;
use App\Models\ReplyModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function create(MessageCreateRequest $request)
    {
        $data = $request->validated();
        //$user = $request->user();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageDS::class)->messageCreate($data);
        return response()->json(['status' => 1, 'msg' => 'create message success！', 'data' => $result]);
    }

    public function update(MessageUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $user = session('user');
        $data['user_id'] = $user['uid'];
        $result = app(MessageDS::class)->messageUpdate($data, $id);
        return response()->json($result);
    }

    public function delete($id)
    {
        $result = app(MessageDS::class)->messageDelete($id);
        return response()->json($result);
    }

    public function list(MessageListRequest $request)
    {
        $data = $request->validated();
        $result = app(MessagePS::class)->getMessageList($data['page'], $data['per_page']);
        return response()->json($result);
    }

    public function info(MessageInfoRequest $request)
    {
        $data = $request->validated();
        //$current_url = $request->getUri();
        $result = app(MessagePS::class)->getInfo($data, $data['page'], $data['per_page']);
        return response()->json(['status' => 1, 'msg' => 'messages of one user', 'data' => $result]);
    }

    /*public function replyList($data, $reply_id)
    {
        $result = array();
        foreach ($data as $k => $v) {
            if (empty($data)) {
                return '';
            }
            if ($v['reply_id'] == $reply_id) {
                $result[$k] = $v;
                unset($data[$k]);
                $result[$k]['replies'] = $this->replyList($data, $v['rid']);
            }
        }
        return $result;
    }*/

    //show one message and one reply , open to show all replies
    public function oneInfo(MessageInfoRequest $request)
    {
        $data = $request->validated();
        //$current_url = $request->getUri();
        $result = app(MessagePS::class)->getOneInfo($data, $data['page'], $data['per_page']);
        return response()->json(['status' => 1, 'msg' => 'one message info', 'data' => $result]);
    }

    public function openAllInfo(OpenAllInfoRequest $request)
    {
        $data = $request->validated();
        $result = app(MessagePS::class)->getOpenAll($data);
        return $result;
    }
}