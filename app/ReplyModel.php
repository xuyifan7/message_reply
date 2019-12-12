<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplyModel extends Model
{
    protected $table = 'reply';

    public $primaryKey = 'rid';

    public $fillable = [
        'user_id', 'message_id', 'reply_content', 'reply_id'
    ];

    public function message()
    {
        return $this->belongsTo('App\MessageModel');
    }

    public function reply_create(array $request)
    {
        $reply = new ReplyModel;
        $reply_data = $reply->create($request);
        return $reply_data;
    }

    public function reply_update(array $request, int $rid)
    {
        $reply = ReplyModel::find($rid);
        $reply_up = $reply->update($request);
        return $reply_up;
    }

    public function reply_delete($rid)
    {
        $reply = ReplyModel::find($rid);
        $replies = ReplyModel::where('reply_id',$rid);
        $result = array();
        $result['status'] = 0;
        if (!is_null($reply)) {
            $res = $reply->delete();
            if($replies->count() > 0) {
                $r_res = $replies->delete();
                if ($res && $r_res) {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                } else {
                    $result['msg'] = "delete message failed!";
                }
            }else{
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
