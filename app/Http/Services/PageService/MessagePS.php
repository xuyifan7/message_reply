<?php


namespace App\Http\Services\PageService;


use App\Events\MessageClickEvent;
use App\Http\Services\DataService\MessageStorageDS;
use App\Models\MessageModel;
use App\Models\ReplyModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;

class MessagePS
{
    public function getMessageList($current_page, $per_page)
    {
        $result = array();
        $result['status'] = 0;
        //$message = MessageModel::latest()->paginate(5);
        $data = MessageModel::latest();
        $message = $this->page($data, $current_page, $per_page);
        $page_start = $this->offSet($current_page, $per_page);
        //$message['message_list'] = $data->offset($page_start)->limit($per_page)->get()->toArray();
        $message_list = $data->offset($page_start)->limit($per_page)->get()->keyBy('user_id')->toArray();
        //dd($message_list);
        //$message get
        $user_ids = $data->pluck('user_id')->unique()->toArray();
        /*$user = UserModel::whereIn('uid', $user_ids)->get()->toArray();
        $user = collect($user)->pluck('uid', 'name')->toArray();*/
        //user keyBy
        $user = UserModel::whereIn('uid', $user_ids)->get()->keyBy('uid')->toArray();
        dd($user);
        foreach ($message_list as $k => &$v) {
            //$user[]
            foreach ($user as $name => $users) {
                if ($v['user_id'] == $users) {
                    $v['name'] = $name;
                }
            }
        }
        $message['message_list'] = $message_list;
        $result['status'] = 1;
        $result['msg'] = "Messages list";
        $result['data'] = $message;
        return $result;
    }

    //show one message's all replies and paginate
    public function getInfo(array $request, $current_page, $per_page)
    {
        $message_info['message'] = MessageModel::where('id', $request['id'])->get();
        $user = UserModel::find(MessageModel::find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        $index = array();
        $rs = array();
        $data_re = $data->toArray();
        foreach ($data_re as &$r) {
            $index[$r['rid']] = &$r;
            $r['replies'] = [];
            if ($r['parent_id'] == 0) {
                $rs[] = &$r;
            } else {
                $index[$r['parent_id']]['replies'][] = &$r;
            }
        }
        $total = ReplyModel::where('message_id', $request['id'])->where('parent_id', 0)->count();
        $last_page = ceil($total / $per_page);
        if ($current_page > $last_page) {
            $current_page = $last_page;
        }
        $page_start = $this->offSet($current_page, $per_page);
        $replies = array_slice($rs, $page_start, $per_page);
        foreach ($replies as $k => &$v) {
            $v['current_page'] = $current_page;
            $v['last_page'] = $last_page;
            $v['per_page'] = $per_page;
            $v['total'] = $total;
        }
        foreach ($message_info['message'] as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $replies;
        }
        return $message_info;
    }

    //show one message and one reply , open to show all replies
    public function getOneInfo(array $request, $current_page, $per_page)
    {
        //$message_info['message'] = MessageModel::where('id', $request['id'])->get();
        $message = new MessageStorageDS();
        $message->putById($request['id']);
        $message_info['message'] = $message->getById($request['id']);
        $user = UserModel::find(MessageModel::find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        //$reply = ReplyModel::where('message_id', $request['id'])->where('parent_id', 0)->oldest()->paginate(2);
        $reply = $this->page(ReplyModel::where('message_id', $request['id'])->where('parent_id', 0)->oldest(), $current_page, $per_page);
        $page_start = $this->offSet($current_page, $per_page);
        $reply['data'] = ReplyModel::where('message_id', $request['id'])->where('parent_id', 0)->oldest()->offset($page_start)->limit($per_page)->get()->toArray();
        foreach ($reply['data'] as $k => &$v) {
            $re_r = collect($data)->whereIn('parent_id', $v['rid'])->first()->toArray();
            if ($v['rid'] == $re_r['parent_id']) {
                $v['replies'] = $re_r;
            }
        }
        foreach ($message_info['message'] as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $reply;
        }
        return $message_info;
    }

    public function getOpenAll(array $request)
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
                if ($r['parent_id'] == $request['rid']) {
                    $rs[] = &$r;
                } else {
                    $index[$r['parent_id']]['replies'][] = &$r;
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

    public function offSet($current_page, $per_page)
    {
        $page_start = ($current_page - 1) * $per_page;
        return $page_start;
    }

    public function page($data, $current_page, $per_page)
    {
        $result = array();
        $total = $data->count();
        $last_page = ceil($total / $per_page);
        if ($current_page > $last_page) {
            $current_page = $last_page;
        }
        $result['current_page'] = $current_page;
        $result['last_page'] = $last_page;
        $result['per_page'] = $per_page;
        $result['total'] = $total;
        return $result;
    }
}