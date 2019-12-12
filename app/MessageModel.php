<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;

class MessageModel extends Model
{

    protected $table = 'message';

    public $primaryKey = 'id';

    public $fillable = [
        'user_id', 'title', 'content'
    ];

    /*protected $appends = ['username'];

    public function getUsernameAttribute()
    {
        $user = session('user');
        return $user['username'];
    }*/

    /*    public function replies()
        {
            return $this->hasMany('App\ReplyModel','message_id');
        }*/

    public function message_create(array $request)
    {
        $message = new MessageModel;
        $mes_data = $message->create($request);
        return $mes_data;
    }

    public function message_update(array $request, int $id)
    {
        $message = MessageModel::find($id);
        $mes_up = $message->update($request);
        return $mes_up;
    }

    public function message_delete(int $id)
    {
        $message = MessageModel::find($id);
        $result = array();
        $result['status'] = 0;
        if (!is_null($message)) {
            $res = $message->delete();
            if ($res) {
                $result['status'] = 1;
                $result['msg'] = "delete message success!";
            } else {
                $result['msg'] = "delete message failed!";
            }
        } else {
            $result['msg'] = "The message not exist!";
        }
        return $result;
    }

    public function show_list()
    {
        $message = $this->orderBy('created_at', 'asc')->get();
        /*$id = $this->pluck('id');
        $replies = ReplyModel::whereIn('message_id', $id);*/
        //$reply_count = ReplyModel::select(DB::raw('count(*) as value'))->where('reply_id', 0)->groupBy('message_id')->get();
        //$message['count'] = $reply_count;
        foreach ($message as $k => $v) {
            $v['count'] = 0;
            //echo $v['id']."\t";
            $reply_count = ReplyModel::select(DB::raw('count(*) as value'))->where('reply_id', 0)->groupBy('message_id');
            $count = $reply_count->where('message_id', $v['id'])->pluck('value');
            //dd(gettype($count));
            if(count($count)>0){
                $v['count'] = $count;
            }
            $v['name'] = UsersModel::where('uid', $v['user_id'])->pluck('name');
        }
        return $message;
    }

    public function show_info(array $request)
    {
        $message_info['message'] = $this->where('id', $request['id'])->get();
        //$message_info['message']['username'] = UsersModel::where('uid', $message_info['message']->pluck('user_id'))->pluck('name');

        $replies = ReplyModel::where('message_id', $request['id']);
        $message_info['replies'] = $replies->orderBy('created_at', 'asc')->paginate(5);
        foreach ($replies as $reply) {
            if ($reply['reply_id'] == 0) {
                $rid = $reply['rid'];
            }
        }
        return $message_info;
    }
}
