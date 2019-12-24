<?php


namespace App\Http\Services\DataService;

use App\Models\MessageModel;
use App\Models\ReplyModel;
use Illuminate\Support\Facades\DB;

class ReplyDS
{
    public function replyCreate(array $request)
    {
        $result = array();
        DB::beginTransaction();
        try {
            if ($request['parent_id'] != 0) {
                $data = ReplyModel::where(['rid' => $request['parent_id'], 'message_id' => $request['message_id']])->get()->toArray();
                if (collect($data)->count() > 0) {
                    $res_reply = ReplyModel::find($request['parent_id'])->increment('r_reply_count');
                    if (!$res_reply) {
                        throw new \Exception("add reply's count failed!", 0);
                    }
                }
            }
            $reply = new ReplyModel;
            $result['status'] = 1;
            $result['msg'] = "create reply success！";
            $result['data'] = $reply->create($request);
            if (!$result['data']) {
                throw new \Exception("create reply failed!", 0);
            }
            $res_mes = MessageModel::find($request['message_id'])->increment('reply_count');
            if (!$res_mes) {
                throw new \Exception("add message's reply_count failed!", 0);
            }
            DB::commit();
            return $result;
        } catch (\Exception $exception) {
            DB::rollback();
            echo "message:" . $exception->getMessage() . "\t";
            echo "code:" . $exception->getCode();
        }
    }

    public function replyUpdate(array $request, int $rid)
    {
        $result = array();
        try {
            $reply = ReplyModel::find($rid);
            if (is_null($reply)) {
                throw new \Exception("The reply not exist!", 0);
            } else {
                $reply_up = $reply->update($request);
                if (!$reply_up) {
                    throw new \Exception("update reply failed!", 0);
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "update reply success！";
                    $result['data'] = $reply_up;
                    return $result;
                }
            }
        } catch (\Exception $exception) {
            echo "message:" . $exception->getMessage() . "\t";
            echo "code:" . $exception->getCode();
        }
    }

    public function replyDelete($rid)
    {
        $result = array();
        try {
            $reply = ReplyModel::find($rid);
            if (is_null($reply)) {
                throw new \Exception("The reply not exist!", 0);
            } else {
                $message_id = $reply->message_id;
                $parent_id = $reply->parent_id;
                $replies = ReplyModel::where('parent_id', $rid);
                $reply->delete();
                if (!$reply->trashed()) {
                    throw new \Exception("delete reply failed!", 0);
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "delete reply success!";
                }
                if ($replies->count() > 0) {
                    $replies->delete();
                    if (!$replies->trashed()) {
                        throw new \Exception("delete replies failed!", 0);
                    } else {
                        $result['status'] = 1;
                        $result['msg'] = "delete replies success!";
                    }
                }
                MessageModel::find($message_id)->decrement('reply_count');
                if ($parent_id != 0) {
                    ReplyModel::find($parent_id)->decrement('r_reply_count');
                }
                return $result;
            }
        } catch (\Exception $exception) {
            echo "message:" . $exception->getMessage() . "\t";
            echo "code:" . $exception->getCode();
        }
    }
}