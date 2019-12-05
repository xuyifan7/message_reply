<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageUpdateRequest;
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
            return response()->json(['msg'=>'create message success！']);
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
            return response()->json(['msg'=>'create message success！']);
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
        }
    }

    public function list(MessageListRequest $request){
        $this->authorize('list', Post::class);
        $data = $request->validated();
        $messages = DB::table('message')->orderBy('created_at')->paginate(5)->get();
        //$messages = Message::all()->orderBy('created_at');
        return response()->json(['status'=>0,'msg'=>'messages list','data'=>$messages]);
    }

/*    public function info(Request $request, $id){
        //$data = $request->validated();
        $message_info = Message::where('id', $id)->orderBy('created_at','desc')->paginate(5)->get();
        return response()->json(['status'=>0,'msg'=>'messages of one user','data'=>$message_info]);
    }*/
}