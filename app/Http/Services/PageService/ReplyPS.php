<?php


namespace App\Http\Services\PageService;


use App\Exceptions\ArgumentNotExistExceptions;
use App\Http\Services\DataService\ReplyDS;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ReplyPS
{
    public function replyCreate(array $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request['parent_id'])) {
                $reply = app(ReplyDS::class)->getReply($request);
                if (empty($reply)) {
                    $this->exception('message_id and reply_id not match!');
                }
                $res_reply = app(ReplyDS::class)->incrementCountByPid($request['parent_id']);
                $this->updateCountFailed($res_reply, "add reply's count failed!");
            }
            $result = app(ReplyDS::class)->insert($request);
            $res_mes = app(ReplyDS::class)->incrementCountByMid($request['message_id']);
            $this->updateCountFailed($res_mes, "add message's reply_count failed!");
            DB::commit();
            return $result;
        } catch (\Exception $exception) {
            DB::rollback();
            $this->exception('commit failed!', -1003);
        }
    }

    public function replyUpdate(array $request, int $rid)
    {
        $reply = app(ReplyDS::class)->getByRid($rid);
        if (is_null($reply)) {
            $this->exception("The reply not exist!");
        } else {
            $reply_up = app(ReplyDS::class)->update($reply, $request);
            return $reply_up;
        }
    }

    public function replyDelete($rid)
    {
        DB::beginTransaction();
        try {
            $reply = app(ReplyDS::class)->getByRid($rid);
            if (is_null($reply)) {
                $this->exception("The reply not exist!");
            }
            app(ReplyDS::class)->deleteReply($reply);
            $replies = app(ReplyDS::class)->getReplyParent($rid);
            if ($replies->count() > 0) {
                app(ReplyDS::class)->deleteReplies($replies);
            }
            $de_mes = app(ReplyDS::class)->decrementCountMes($reply->message_id);
            $this->updateCountFailed($de_mes, "decrease reply's count failed!");
            if ($reply->parent_id != 0) {
                $de_re = app(ReplyDS::class)->decrementCountRe($reply->parent_id);
                $this->updateCountFailed($de_re, "decrease reply's count failed!");
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            $this->exception('delete reply failed!', -1004);
        }
        return true;
    }

    public function exception($message, int $code = 500)
    {
        throw new ArgumentNotExistExceptions($message, $code);
    }

    public function updateCountFailed($data, $message)
    {
        if (!$data) {
            return $this->exception($message, -1);
        }
    }
}