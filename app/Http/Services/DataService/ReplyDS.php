<?php


namespace App\Http\Services\DataService;


use App\Models\ReplyModel;

class ReplyDS
{
    public function replyCreate(array $request)
    {
        $result = array();
        if ($request['reply_id']) {
            $r_mes = ReplyModel::find($request['reply_id'])->message_id;
            $message_id = intval($request['message_id']);
            if ($r_mes != $message_id) {
                $result['status'] = 0;
                $result['msg'] = "请输入该留言正确的回复ID";
            } else {
                $reply = new ReplyModel;
                $result['status'] = 1;
                $result['msg'] = "create reply success！";
                $result['data'] = $reply->create($request);
            }
        } else {
            $reply = new ReplyModel;
            $result['status'] = 1;
            $result['msg'] = "create reply success！";
            $result['data'] = $reply->create($request);
        }
        return $result;
    }

    public function replyUpdate(array $request, int $rid)
    {
        $reply = ReplyModel::find($rid);
        $result = array();
        $result['status'] = 0;
        $reply_up = $reply->update($request);
        if ($reply_up) {
            $result['status'] = 1;
            $result['msg'] = "update reply success！";
            $result['data'] = $reply_up;
        }
        return $result;
    }

    public function replyDelete($rid)
    {
        $reply = ReplyModel::find($rid);
        $replies = ReplyModel::where('reply_id', $rid);
        $result = array();
        $result['status'] = 0;
        if (!is_null($reply)) {
            $res = $reply->delete();
            if ($replies->count() > 0) {
                $r_res = $replies->delete();
                if ($res && $r_res) {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                } else {
                    $result['msg'] = "delete message failed!";
                }
            } else {
                if ($res) {
                    $result['status'] = 1;
                    $result['msg'] = "delete reply success!";
                } else {
                    $result['msg'] = "delete reply failed!";
                }
            }
        } else {
            $result['msg'] = "The reply not exist!";
        }
        return $result;
    }
}