<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Requests\ReplyCreateRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    //
    public function create(MessageCreateRequest $request){
        $data = $request->validated();
        //if ($request->validated()){
            $message = new Message;
            $message->title = $request->input('title');
            $message->content = $request->input('content');
            $message->post_time = CREATED_AT;
            $message->save();
            return response()->json(['status'=>1,'msg'=>'create message success！','data'=>$message]);
        /*}else{
            return response()->json('create message failed!');
        }*/
    }

    public function update(MessageUpdateRequest $request){
        //if ($request->validated()){
            //$mes = array();
            $id = $request->input('id');
            $title = $request->input('title');
            $content = $request->input('content');
            $post_time = CREATED_AT;
            $message = Message::find($id);
            $message->title = $title;
            $message->content = $content;
            $message->post_time = $post_time;
            $message->save();

        //}
    }

    public function delete(Request $request){
        //
        $id = 1;
        $delete_mes = Message::where('id', $id)->delete();
    }

    public function reply_create(ReplyCreateRequest $request){
        $data = $request->validated();
            $message = new Message;
            $message->reply_content = $request->input('reply_content');
            $message->reply_time = CREATED_AT;
            $message->reply_name = session('user');
            $message->save();
            return response()->json(['status'=>1,'msg'=>'create message success！','data'=>$message]);
        /*}else{
            return response()->json('create message failed!');
        }*/
    }

    public function reply_update(ReplyUpdateRequest $request){

    }

    public function reply_delete(Request $request){

    }

    public function list(){

    }

    public function info(){

    }
}
