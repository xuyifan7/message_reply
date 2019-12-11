<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageInfoRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Message;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{

    public function replies(){
        return $this->hasMany('App\Reply');
    }

    public function create(MessageCreateRequest $request){
        $data = $request->validated();
        $result = app(Message::class)->message_create($data);
        return response()->json(['status'=>1,'msg'=>'create message success！','data'=>$result]);
    }

    public function update(MessageUpdateRequest $request, $id){
        $data = $request->validated();
        $result = app(Message::class)->message_update($data, $id);
        return response()->json(['status' => 1, 'msg' => 'update message success！', 'data' => $result]);
    }

    public function delete($id){
        $result = app(Message::class)->message_delete($id);
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