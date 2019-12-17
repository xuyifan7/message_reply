<?php

namespace App\Models;

use App\Http\Controllers\MessageController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /*public function messageCreate(array $request)
    {
        $message = new MessageModel;
        $mes_data = $message->create($request);
        return $mes_data;
    }*/

    /*public function messageUpdate(array $request, int $id)
    {
        $message = MessageModel::find($id);
        $mes_up = $message->update($request);
        return $mes_up;
    }*/

    /*public function messageDelete(int $id)
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
    }*/

    public function showList()
    {
        $message = $this->latest()->paginate(5);
        $messages = $this->latest()->get();
        /*$message_ids = collect($messages)->pluck('id')->toArray();
        $result = ReplyModel::select(DB::raw('message_id, count(*) as value'))->where('reply_id', 0)->whereIn('message_id', $message_ids)->groupBy('message_id')->get()->toArray();
        $result = collect($result)->pluck('message_id', 'value')->toArray();
        //dd($result);*/
        $user_ids = collect($messages)->pluck('user_id')->unique()->toArray();
        $user = UserModel::whereIn('uid', $user_ids)->get()->toArray();
        $user = collect($user)->pluck('uid', 'name')->toArray();
        //dd($user);
        foreach ($message as $k => $v) {
            $count = ReplyModel::where('message_id', $v['id'])->count();
            $v['count'] = $count;
            /*$v['count'] = 0;
            foreach ($result as $count => $mes) {
                if ($v['id'] == $mes) {
                    $v['count'] = $count;
                }
            }*/
            foreach ($user as $name => $users) {
                if ($v['user_id'] == $users) {
                    $v['name'] = $name;
                }
            }
        }
        return $message;
    }

    //show one message's all replies and paginate
    public function showInfo(array $request, $current_url)
    {
        $message_info['message'] = $this->where('id', $request['id'])->get();
        $user = UserModel::find($this->find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        //$replies = app(MessageController::class)->replyList($data, 0);
        $index = array();
        $rs = array();
        $data_re = $data->toArray();
        foreach ($data_re as &$r) {
            $index[$r['rid']] = &$r;
            $r['replies'] = [];
            if ($r['reply_id'] == 0) {
                $rs[] = &$r;
            } else {
                $index[$r['reply_id']]['replies'][] = &$r;
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($rs);
        $perPage = 1;
        $currentPageSearchResults = $collection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        $paginatedSearchResults->setPath($current_url);
        $replies = $paginatedSearchResults;

        foreach ($message_info['message'] as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $replies;
        }
        return $message_info;
    }

    //show one message and one reply , open to show all replies
    public function showOneInfo(array $request, $current_url)
    {
        $message_info['message'] = $this->where('id', $request['id'])->get();
        $user = UserModel::find($this->find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        /*$group = ReplyModel::select(DB::raw('reply_id, count(*) as count'))->where('message_id', $request['id'])->where('reply_id', '<>', 0)->groupBy('reply_id')->get();
        $count = collect($group)->pluck('count', 'reply_id');*/
        $reply = ReplyModel::where('message_id', $request['id'])->where('reply_id', 0)->oldest()->paginate(2);
        $reply->withPath($current_url);
        foreach ($reply as $k => $v) {
            $count = ReplyModel::where('reply_id', $v['rid'])->count();
            $v['count'] = $count;
            /*$v['count'] = 0;
            foreach ($count as $rid => $cou) {
                if ($v['rid'] == $rid) {
                    $v['count'] = $cou;
                }
            }*/

            $re_r = collect($data)->whereIn('reply_id', $v['rid'])->first()->toArray();
            //show two
            /*$re = collect($data)->whereIn('reply_id', $v['rid'])->toArray();
            $re_two = array_slice($re, 0, 2);
            foreach ($re_two as $k => $v) {
                var_dump($v);echo "\t";
            }
            //var_dump($re_two);echo "\t";*/

            if ($v['rid'] == $re_r['reply_id']) {
                $v['replies'] = $re_r;
            }
        }
        foreach ($message_info['message'] as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $reply;
        }
        return $message_info;
    }

    public function openAll(array $request)
    {
        $result = array();
        $result['status'] = 0;
        $mes_id = ReplyModel::find($request['rid'])->message_id;
        $id = intval($request['id']);
        if ($id != $mes_id) {
            $result['msg'] = "请输入该条留言下正确的回复ID!";
        } else {
            $reply_info['reply'] = ReplyModel::where('rid', $request['rid'])->get();
            $user = UserModel::find(ReplyModel::find($request['rid'])->user_id)->name;
            $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
            $index = array();
            $rs = array();
            $data_re = $data->toArray();
            foreach ($data_re as &$r) {
                $index[$r['rid']] = &$r;
                $r['replies'] = [];
                if ($r['reply_id'] == $request['rid']) {
                    $rs[] = &$r;
                } else {
                    $index[$r['reply_id']]['replies'][] = &$r;
                }
            }
            foreach ($reply_info['reply'] as $k => $v) {
                $v['name'] = $user;
                $v['replies'] = $rs;
            }
            $result['status'] = 1;
            $result['msg'] = "open all info for one reply";
            $result['data'] = $reply_info;
        }
        return $result;
    }

}
