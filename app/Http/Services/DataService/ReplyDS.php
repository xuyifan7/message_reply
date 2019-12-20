<?php


namespace App\Http\Services\DataService;


use App\Models\MessageModel;
use App\Models\ReplyModel;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ReplyDS
{
    public function replyCreate(array $request)
    {
        $result = array();
        if (isset($request['reply_id'])) {
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
            ReplyModel::find($request['reply_id'])->increment('r_reply_count');
        } else {
            $reply = new ReplyModel;
            $result['status'] = 1;
            $result['msg'] = "create reply success！";
            $result['data'] = $reply->create($request);
        }
        MessageModel::find($request['message_id'])->increment('reply_count');
        return $result;
    }

    public function replyUpdate(array $request, int $rid)
    {
        $result = array();
        $result['status'] = 0;
        DB::beginTransaction();
        try {
            $reply = ReplyModel::find($rid);
            $reply_up = $reply->update($request);
            if (!$reply_up) {
                throw new \Exception("update reply failed!", 0);
            } else {
                $result['status'] = 1;
                $result['msg'] = "update reply success！";
                $result['data'] = $reply_up;
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            echo $exception->getMessage();
            echo $exception->getCode();
        }
        return $result;
    }

    public function replyDelete($rid)
    {
        $reply = ReplyModel::find($rid);
        $result = array();
        $result['status'] = 0;
        if (!is_null($reply)) {
            DB::beginTransaction();
            try {
                $message_id = $reply->message_id;
                $reply_id = $reply->reply_id;
                $replies = ReplyModel::where('reply_id', $rid);
                $res = $reply->delete();
                if (!$res) {
                    throw new \Exception("delete reply failed!", 0);
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "delete reply success!";
                }
                DB::commit();
                if ($replies->count() > 0) {
                    $r_res = $replies->delete();
                    if (!$r_res) {
                        throw new \Exception("delete replies failed!", 0);
                    } else {
                        $result['status'] = 1;
                        $result['msg'] = "delete reply success!";
                    }
                    DB::commit();
                }
            } catch (\Exception $exception) {
                DB::rollback();
                echo $exception->getMessage();
                echo $exception->getCode();
            }
            MessageModel::find($message_id)->decrement('reply_count');
            if ($reply_id != 0) {
                ReplyModel::find($reply_id)->decrement('r_reply_count');
            }
        } else {
            $result['msg'] = "The reply not exist!";
        }
        return $result;
    }
}