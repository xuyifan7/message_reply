<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'reply';

    public $primaryKey = 'rid';

    public $fillable = [
        'user_id', 'message_id', 'reply_content', 'reply_id'
    ];

    public function message()
    {
        return $this->belongsTo('App\Message');
    }

    public function reply_create(array $request)
    {
        $user = session()->get('user');
        $request['user_id'] = $user['uid'];
        $reply = new Reply;
        $reply_data = $reply->create($request);
        return $reply_data;
    }

    public function reply_update(array $request, int $rid)
    {
        $reply = Reply::find($rid);
        $user = session()->get('user');
        $request['user_id'] = $user['uid'];
        $reply_up = $reply->update($request);
        return $reply_up;
    }

    public function reply_delete($rid)
    {
        $reply = Reply::find($rid);
        $replies = Reply::where('reply_id',$rid);
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
