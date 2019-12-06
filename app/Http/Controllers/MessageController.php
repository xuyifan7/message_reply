<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Message;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Requests\MessageListRequest;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function create(MessageCreateRequest $request){
        $data = $request->validated();
        $user = session()->get('user');
        if ($user){
            $data['user_id'] = $user['uid'];
            $message = new Message;
            $mes_data = $message->create($data);
            return response()->json(['status'=>1,'msg'=>'create message success！','data'=>$mes_data]);
        }else{
            return response()->json(['msg'=>'Please login！']);
        }
    }

    public function update(MessageUpdateRequest $request, $id){
        $data = $request->validated();
        $message = Message::find($id);
        $user = session()->get('user');
        if ($user) {
            $data['user_id'] = $user['uid'];
            $mes_up = $message->update($data);
            return response()->json(['status' => 1, 'msg' => 'update message success！', 'data' => $mes_up]);
        }else{
            return response()->json(['msg'=>'Please login！']);
        }
    }

    public function delete($id){
        $user = session()->get('user');
        if ($user) {
            $message = Message::find($id);
            $res = $message->delete();
            if ($res) {
                return response()->json(['status' => 1, 'msg' => 'delete message success!']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'delete message failed!']);
            }
        }else{
            return response()->json(['msg'=>'Please login！']);
        }
    }

    public function reply_create(ReplyCreateRequest $request){
        //dd(123);
        $data = $request->validated();
        $user = session()->get('user');
        if($user) {
            $data['user_id'] = $user['uid'];
            //$mes_id = Message::where('reply_id',0)->id;
            $reply = new Message;
            $reply_data = $reply->craete($data);
            $reply_data['name'] = $user['name'];
            return response()->json(['status' => 1, 'msg' => 'create reply success！', 'data' => $reply_data]);
        }else{
            return response()->json(['msg'=>'Please login！']);
        }
    }

    public function reply_update(ReplyUpdateRequest $request, $reply_id, $id){
        $data = $request->validated();
        $reply = Message::find($reply_id);
        $user = session()->get('user');
        if($user) {
            $data['user_id'] = $user['uid'];
            $reply_up = $reply->where('id',$id)->update($data);
            return response()->json(['status' => 1, 'msg' => 'update reply success！', 'data' => $reply_up]);
        }else{
            return response()->json(['msg'=>'Please login!']);
        }
    }

    public function reply_delete($reply_id, $id){
        $user = session()->get('user');
        if ($user) {
            $message = Message::find($reply_id);
            $res = $message->where('id',$id)->delete();
            if ($res) {
                return response()->json(['status' => 1, 'msg' => 'delete reply success!']);
            } else {
                return response()->json(['msg'=>'Please login！']);
            }
        }
    }

    public function list(MessageListRequest $request){
        //dd('123');
        $data = $request->validated();
        $user = session()->get('user');
        if($user){
            //$name = DB::table('message')->join('user', 'message.user_id', '=', 'user.uid')->select('user.name')->where('message.user_id',$user['uid']);
            $mes = Message::where('reply_id',0)->orderBy('created_at')->paginate(5);
            //var_dump($mes);
            $data['mes'] = $mes;
            //$data['reply_count'] = DB::table('message')->where('reply_id','<>',0)->groupBy('reply_id')->count();
            return response()->json(['status'=>1,'msg'=>'Messages list','data'=>$mes]);
        }else{
            return response()->json(['msg'=>'Please login！']);
        }
    }

   public function info(MessageInfoRequest $request, $id){
       $data = $request->validated();
       $user = session()->get('user');
       if($user){
           $message_info = Message::where('id', $id)->orWhere('reply_id',$id)->orderBy('created_at','desc')->paginate(5);
           return response()->json(['status'=>0,'msg'=>'messages of one user','data'=>$message_info]);
       }else{
           return response()->json(['msg'=>'Please login！']);
       }
    }
}