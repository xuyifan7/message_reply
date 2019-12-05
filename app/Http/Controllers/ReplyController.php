<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Message;
use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function reply_create(ReplyCreateRequest $request, $id){
        $data = $request->validated();
        $reply = new Reply;
        $reply->reply_content = $request->input('reply_content');
        //$message->reply_time = CREATED_AT;
        $reply->reply_name = session('user');
        $reply->save();
        return response()->json(['status'=>1,'msg'=>'create message success！','data'=>['username'=>reply_name,'reply'=>$reply]]);
    }

    public function reply_update(ReplyUpdateRequest $request, $id){

    }

    public function reply_delete(Request $request, $id){
        $reply = Reply::find($id);
        $res = $reply->delete();
        if($res){
            return response()->json(['status'=>1,'msg'=>'delete message success!']);
        }else{
            return response()->json(['status'=>0,'msg'=>'delete message failed!']);
        }
    }

    public function info(Request $request, $id){
        //$data = $request->validated();
        $message_info = Message::where('id', $id)->orderBy('created_at','desc')->paginate(5)->get();
        return response()->json(['status'=>0,'msg'=>'messages of one user','data'=>$message_info]);
    }
}
