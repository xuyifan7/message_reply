<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'message';

    public $primaryKey = 'id';

    public $fillable = [
        'user_id','title','content'
    ];

    public function replies(){
        return $this->hasMany('App\Reply');
    }


    public function message_create(array $request){
        $user = session()->get('user');
        $request['user_id'] = $user['uid'];
        $message = new Message;
        $mes_data = $message->create($request);
        return $mes_data;
    }

    public function message_update(array $request, int $id){
        $message = Message::find($id);
        $user = session()->get('user');
        $request['user_id'] = $user['uid'];
        $mes_up = $message->update($request);
        return $mes_up;
    }

    public function message_delete(int $id){
        $message = Message::find($id);
        $result =array();
        $result['status'] = 0;
        if (!is_null($message)) {
            $res = $message->delete();
            $reply = Message::where('reply_id',$id);
            if($reply->count() > 0){
                $r_res = $reply->delete();
                if ($res && $r_res) {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                } else {
                    $result['msg'] = "delete message failed!";
                }
            }else{
                if ($res) {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                }else{
                    $result['msg'] = "delete message failed!";
                }
            }
        }else {
            $result['msg'] = "The message not exist!";
        }
        return $result;
    }

    public function show_list(){
        $message = Message::where('reply_id',0)->orderBy('created_at','desc')->paginate(5);
        /*$mes = $message->toArray();
         * foreach ($mes['data'] as $mes_data){
            //$mes_data['reply_count'] = DB::table('message')->where('reply_id',$mes_data['id'])->groupBy('reply_id')->count();
            //$mes_data['name'] = DB::table('message')->join('user', 'message.user_id','=', 'user.uid')->select('user.name')->where('message.user_id',$mes_data['user_id']);
        }*/
        return $message->toArray();
    }

    public function show_info(array $request){
        $user = session()->get('user');
        $message_info['message'] = Message::where('id', $request['id'])->With(['replies'=>function($values){
            return $values->paginate(5);
        }])->orderBy('created_at','desc')->paginate(5);
        $message = Message::find($request['id']);
//        $message_info['message']['reply'] = $message->replies()->orderBy('created_at','desc')->paginate(5);
        $message_info['username'] = $user['username'];
        return $message_info;
    }
}
