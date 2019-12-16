<?php

namespace App;

use App\Http\Controllers\MessageController;
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

    public function messageCreate(array $request)
    {
        $message = new MessageModel;
        $mes_data = $message->create($request);
        return $mes_data;
    }

    public function messageUpdate(array $request, int $id)
    {
        $message = MessageModel::find($id);
        $mes_up = $message->update($request);
        return $mes_up;
    }

    public function messageDelete(int $id)
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

    public function showList()
    {
        /*$message = $this->orderBy('created_at', 'desc')->paginate(5);
        $messages = $this->orderBy('created_at', 'desc')->get();*/
        $message = $this->latest()->paginate(5);
        $messages = $this->latest()->get();
        $message_ids = collect($messages)->pluck('id')->toArray();
        $user_ids = collect($messages)->pluck('user_id')->unique()->toArray();
        $result = ReplyModel::select(DB::raw('message_id, count(*) as value'))->where('reply_id', 0)->whereIn('message_id', $message_ids)->groupBy('message_id')->get()->toArray();
        $result = collect($result)->pluck('message_id', 'value')->toArray();
        //dd($result);
        $user = UserModel::whereIn('uid', $user_ids)->get()->toArray();
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

    public function showInfo(array $request)
    {
        $message_info['message'] = $this->where('id', $request['id'])->paginate(5);
        $user = UserModel::find($this->find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        /*$reply = collect($data)->keyBy('rid')->get();
        $replies = app(MessageController::class)->replyList($reply, 0);*/
        $replies = app(MessageController::class)->replyList($data, 0);
        foreach ($message_info['message'] as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $replies;
        }
        return $message_info;
    }

}
