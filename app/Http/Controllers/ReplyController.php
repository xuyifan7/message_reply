<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Http\Services\PageService\ReplyPS;

class ReplyController extends Controller
{
    public function replyCreate(ReplyCreateRequest $request)
    {
        $data = $request->validated();
        $user = $request->getUser();
        $data['user_id'] = $user['uid'];
        $result = app(ReplyPS::class)->replyCreate($data);
        return $result ? app(ApiResponse::class)->success($result->toArray(), 'create reply success!') : app(ApiResponse::class)->error([]);
    }

    public function replyUpdate(ReplyUpdateRequest $request, $rid)
    {
        $data = $request->validated();
        $user = $request->getUser();
        $data['user_id'] = $user['uid'];
        $result = app(ReplyPS::class)->replyUpdate($data, $rid);
        return $result ? app(ApiResponse::class)->success([], 'update reply success!') : app(ApiResponse::class)->error([]);
    }

    public function replyDelete($rid)
    {
        $result = app(ReplyPS::class)->replyDelete($rid);
        return $result ? app(ApiResponse::class)->success([], 'delete reply success!') : app(ApiResponse::class)->error([]);
    }

}
