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
        $message = $this->orderBy('created_at', 'asc')->paginate(5);
        $messages = $this->orderBy('created_at', 'asc')->get();
        $message_ids = collect($messages)->pluck('id')->toArray();
        $user_ids = collect($messages)->pluck('user_id')->unique()->toArray();
        $result = ReplyModel::select(DB::raw('message_id, count(*) as value'))->where('reply_id', 0)->whereIn('message_id', $message_ids)->groupBy('message_id')->get()->toArray();
        $result = collect($result)->pluck('message_id', 'value')->toArray();
        //dd($result);
        $user = UsersModel::whereIn('uid', $user_ids)->get()->toArray();
        $user = collect($user)->pluck('uid', 'name')->toArray();
        //dd($user);
        foreach ($message as $k => $v) {
            $v['count'] = 0;
            foreach ($result as $count => $mes) {
                if ($v['id'] == $mes) {
                    $v['count'] = $count;
                }
            }
            foreach ($user as $name => $users) {
                if ($v['user_id'] == $users) {
                    $v['name'] = $name;
                }
            }
        }
        return $message;
    }

    public function show_info(array $request)
    {
        $message_info['message'] = $this->where('id', $request['id'])->get();
        $user = UsersModel::find($this->find($request['id'])->user_id)->name;
        $reply = ReplyModel::where('message_id', $request['id'])->orderBy('created_at', 'asc')->paginate(5);
        $replies = ReplyModel::where('message_id', $request['id'])->orderBy('created_at', 'asc')->get();
        $reply_info = collect($reply)->toArray();
        //dd($replies->toArray());

        $replies_group = ReplyModel::select(DB::raw('reply_id, count(*) as count'))->where('message_id', $request['id'])->where('reply_id', '<>', 0)->groupBy('reply_id')->get()->toArray();
        $groups = count($replies_group);
        $replies_group = collect($replies_group)->pluck('count', 'reply_id')->toArray();

        //dd($replies_group);

        $message_replies = collect($replies)->where('reply_id', 0);

        /*//message's reply
        $message_replies = collect($replies)->where('reply_id', 0);
        //dd($message_replies);

        //if reply's reply exist
        $message_replies_ids = $message_replies->pluck('rid');
        $rr = collect($replies)->whereIn('reply_id', $message_replies_ids);
        if($rr){
            $r_ids = $rr->pluck('reply_id');
        }
        //dd($rr->toArray());*/

        foreach ($message_info['message'] as $k => $v) {
            $v['name'] = $user;
            //$v['replies'] = $reply_info;
            $v['replies'] = $message_replies->toArray();
            $r = $message_replies->where('rid', 26)->toArray();
            //dd($r);
            foreach ($r as $k => $v){
                echo $v['reply_content'];
                $v['replies'] = 1;
            }
            /*foreach ($replies_group as $rid => $count){
                $message_replies->find($rid);
            }*/
        }

        /*$replies = ReplyModel::where('message_id', $request['id']);
        $message_info['replies'] = $replies->orderBy('created_at', 'asc')->paginate(5);
        foreach ($replies as $reply) {
            if ($reply['reply_id'] == 0) {
                $rid = $reply['rid'];
            }
        }*/
        return $message_info;
    }
}
