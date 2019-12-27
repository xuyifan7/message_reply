<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageListRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\OpenAllInfoRequest;
use App\Http\Services\DataService\MessageDS;
use App\Http\Services\PageService\MessagePS;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function create(MessageCreateRequest $request)
    {
        $data = $request->validated();
        $user = $request->getUser();
        $data['user_id'] = $user['uid'];
        $result = app(MessageDS::class)->messageCreate($data);
        return $result ? app(ApiResponse::class)->success($result, 'create message success!') : app(ApiResponse::class)->error([]);
    }

    public function update(MessageUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $user = $request->getUser();
        $data['user_id'] = $user['uid'];
        $result = app(MessagePS::class)->messageUpdate($data, $id);
        return $result ? app(ApiResponse::class)->success([], 'update message success!') : app(ApiResponse::class)->error([]);
    }

    public function delete($id)
    {
        $result = app(MessagePS::class)->messageDelete($id);
        return $result ? app(ApiResponse::class)->success([], 'delete message success!') : app(ApiResponse::class)->error([]);
    }

    public function list(MessageListRequest $request)
    {
        $data = $request->validated();
        $result = app(MessagePS::class)->getMessageList($data);
        return $result ? app(ApiResponse::class)->success($result, 'Messages list') : app(ApiResponse::class)->error([]);
    }

    public function info(MessageInfoRequest $request)
    {
        $data = $request->validated();
        $result = app(MessagePS::class)->getInfo($data, $data['page'], $data['per_page']);
        return response()->json(['status' => 1, 'msg' => 'messages of one user', 'data' => $result]);
    }

    //show one message and one reply , open to show all replies
    public function oneInfo(MessageInfoRequest $request)
    {
        $data = $request->validated();
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