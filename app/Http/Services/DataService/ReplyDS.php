<?php


namespace App\Http\Services\DataService;

use App\Models\MessageModel;
use App\Models\ReplyModel;
use App\Traits\DBTransaction;
use Illuminate\Support\Facades\DB;

class ReplyDS
{
    /*use DBTransaction;

    private $reply = null;

    public function __construct(ReplyModel $reply)
    {
        $this->reply = $reply;
    }*/

    public function insert($data)
    {
        $reply = new ReplyModel;
        return $reply->create($data);
    }

    public function update($reply, $data)
    {
        return $reply->update($data);
    }

    public function deleteReply($reply)
    {
        return $reply->delete();
    }

    public function deleteReplies($replies)
    {
        return $replies->delete();
    }

    public function getByRid($rid)
    {
        return ReplyModel::find($rid);
    }

    public function incrementCountByPid($pid)
    {
        return ReplyModel::find($pid)->increment('r_reply_count');
    }

    public function incrementCountByMid($mid)
    {
        return MessageModel::find($mid)->increment('reply_count');
    }

    public function getReply($data)
    {
        return ReplyModel::where(['rid' => $data['parent_id'], 'message_id' => $data['message_id']])->get()->toArray();
    }

    public function getReplyParent($rid)
    {
        return ReplyModel::where('parent_id', $rid);
    }

    public function decrementCountMes($message_id)
    {
        return MessageModel::find($message_id)->decrement('reply_count');
    }

    public function decrementCountRe($parent_id)
    {
        return ReplyModel::find($parent_id)->decrement('r_reply_count');
    }
}