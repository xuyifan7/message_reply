<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Message;
use http\Env\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MessageListRequest;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function create(MessageCreateRequest $request){
        $data = $request->validated();
        $result = app(Message::class)->message_create($data);
        /*$user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $message = new Message;
        $mes_data = $message->create($data);*/
        return response()->json(['status'=>1,'msg'=>'create message success！','data'=>$result]);
    }

    public function update(MessageUpdateRequest $request, $id){
        $data = $request->validated();
        $result = app(Message::class)->message_update($data, $id);
        /*$message = Message::find($id);
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $mes_up = $message->update($data);*/
        return response()->json(['status' => 1, 'msg' => 'update message success！', 'data' => $result]);
    }

    public function delete($id){
        $result = app(Message::class)->message_delete($id);
        /*$message = Message::find($id);
        if (!is_null($message)) {
            $res = $message->delete();
            $reply = Message::where('reply_id',$id);
            if(!is_null($reply)){
                $r_res = $reply->delete();
                if ($res && $r_res) {
                    return response()->json(['status' => 1, 'msg' => 'delete message success!']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'delete message failed!']);
                }
            }else{
                if ($res) {
                    return response()->json(['status' => 1, 'msg' => 'delete message success!']);
                }else{
                    return response()->json(['status' => 0, 'msg' => 'delete message failed!']);
                }
            }
        }else {
            return response()->json(['status' => 0, 'msg' => 'The message not exist!']);
        }*/
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

    public function reply_create(ReplyCreateRequest $request){
        $data = $request->validated();
        $result = app(Message::class)->reply_create($data);
        /*$user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $reply = new Message;
        $reply_data = $reply->create($data);*/
        return response()->json(['status' => 1, 'msg' => 'create reply success！', 'data' => $result]);
    }

    public function reply_update(ReplyUpdateRequest $request, $id){
        $data = $request->validated();
        $result = app(Message::class)->reply_update($data, $id);
        /*$reply = Message::find($data['reply_id']);
        $user = session()->get('user');
        $data['user_id'] = $user['uid'];
        $reply_up = $reply->where('id',$id)->update($data);*/
        return response()->json(['status' => 1, 'msg' => 'update reply success！', 'data' => $result]);
    }

    public function reply_delete($reply_id, $id){
        $result = app(Message::class)->reply_delete($reply_id, $id);
        /*$message = Message::find($reply_id);
        if (!is_null($message)) {
            $res = $message->where('id', $id)->delete();
            if ($res) {
                return response()->json(['status' => 1, 'msg' => 'delete reply success!']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'delete reply failed!']);
            }
        }else {
            return response()->json(['status' => 0, 'msg' => 'The message not exist!']);
        }*/
        return response()->json(['status' => $result['status'], 'msg' => $result['msg']]);
    }

    public function list(){
        $result = app(Message::class)->show_list();
        /*$message = Message::where('reply_id',0)->orderBy('created_at','desc')->paginate(5);
        $mes = $message->toArray();
        //var_dump($mes['data']);*/
        return response()->json(['status'=>1,'msg'=>'Messages list','data'=>$result]);
    }

    public function info(MessageInfoRequest $request){
        $data = $request->validated();
        $result = app(Message::class)->show_info($data);
        /*$user = session()->get('user');
        $message_info['message'] = Message::where('id', $data['id'])->orWhere('reply_id',$data['id'])->orderBy('created_at','desc')->paginate(5);
        $message_info['username'] = $user['username'];*/
        return response()->json(['status'=>0,'msg'=>'messages of one user','data'=>$result]);
    }
}