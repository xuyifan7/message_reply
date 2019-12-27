<?php


namespace App\Http\Services\PageService;

use App\Events\MessageClickEvent;
use App\Exceptions\BaseExceptions;
use App\Http\Services\DataService\MessageDS;
use App\Http\Services\DataService\MessageStorageDS;
use App\Http\Services\DataService\ReplyDS;
use App\Models\MessageModel;
use App\Models\ReplyModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MessagePS
{
    public function messageUpdate(array $request, int $id)
    {
        $message = app(MessageDS::class)->getById($id);
        if (is_null($message)) {
            $this->exception("The message not exist!");
        } else {
            $mes_up = app(MessageDS::class)->update($message, $request);
            return $mes_up;
        }
    }

    public function messageDelete(int $id)
    {
        DB::beginTransaction();
        try {
            $message = app(MessageDS::class)->getById($id);
            //MessageModel::withTrashed()->where('id', $id)->restore();die;
            if (is_null($message)) {
                $this->exception("The message not exist!");
            } else {
                app(MessageDS::class)->delete($message);
                /*if (!$message->trashed()) {
                    throw new \Exception("delete message failed!", 0);
                }*/
                $reply = app(MessageDS::class)->getReply($id);
                if ($reply->count() > 0) {
                    app(MessageDS::class)->delete($reply);
                    /*if (!$reply->trashed()) {
                        throw new \Exception("delete message's reply failed!", 0);
                    }*/
                }
                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollback();
            $this->exception($exception->getMessage(), -1005);
        }
        return true;
    }

    public function getMessageList($current_page, $per_page)
    {
        $result = array();
        $mes = new MessageModel();
        $message = $this->page($mes, $current_page, $per_page);
        $page_start = $this->offSet($current_page, $per_page);
        $message_list = $mes->offset($page_start)->limit($per_page)->latest()->get()->toArray();
        $user_ids = collect($message_list)->pluck('user_id')->unique()->toArray();
        $user = UserModel::whereIn('uid', $user_ids)->get()->toArray();
        //$user = collect($user)->pluck('name', 'uid')->toArray();
        $user = collect($user)->keyBy('uid')->toArray();
        foreach ($message_list as $k => &$v) {
            $v['name'] = $user[$v['user_id']]['name'];
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
        //$message_info['message'] = MessageModel::find($request['id'])->get();
        try {
            $mes = MessageModel::findOrFail($request['id']);
            $uid = $mes->user_id;
            $user = UserModel::findOrFail($uid);
            $reply = ReplyModel::where('message_id', $request['id']);
            $index = array();
            $rs = array();
            $data = $reply->latest()->get()->toArray();
            foreach ($data as &$r) {
                $index[$r['rid']] = &$r;
                $r['replies'] = [];
                if ($r['parent_id'] == 0) {
                    $rs[] = &$r;
                } else {
                    $index[$r['parent_id']]['replies'][] = &$r;
                }
            }
            $total = collect($data)->where('parent_id', 0)->count();
            $last_page = ceil($total / $per_page);
            if ($current_page > $last_page) {
                $current_page = $last_page;
            }
            $page_start = $this->offSet($current_page, $per_page);
            $replies = array_slice($rs, $page_start, $per_page);
            $message = $mes->toArray();
            $message['name'] = $user->name;
            $message['replies']['data'] = $replies;
            $message['replies']['last_page'] = $last_page;
            $message['replies']['total'] = $total;
            $message_info['message'] = $message;
            return $message_info;
        } catch (\Exception $exception) {
            echo "message:" . $exception->getMessage() . "\t";
            echo "code:" . $exception->getCode();
        }
    }

    //show one message and one reply , open to show all replies
    public function getOneInfo(array $request, $current_page, $per_page)
    {
        //$message_info['message'] = MessageModel::where('id', $request['id'])->get();
        $message = new MessageStorageDS();
        $mes = $message->getById($request['id']);
        if (!$mes) {
            $message->putById($request['id']);
            $mes = $message->getById($request['id']);
        }
        /*$m = MessageModel::find($request['id']);
        $ip = request()->getClientIp();
        event(new MessageClickEvent($m, $ip));*/
        $user = UserModel::findOrFail($mes->pluck('user_id')->first())->name;
        $re = ReplyModel::where('message_id', $request['id']);
        //$reply = ReplyModel::where('message_id', $request['id'])->where('parent_id', 0)->oldest()->paginate(2);
        $data = $re->where('parent_id', 0);
        $reply = $this->page($data, $current_page, $per_page);
        $page_start = $this->offSet($current_page, $per_page);
        $reply['data'] = $data->offset($page_start)->limit($per_page)->get()->toArray();
        //dd($reply['data']);
        /*foreach ($reply['data'] as $k => $v) {
            $re_r = $re->latest()->whereIn('parent_id', $v['rid'])->keyBy('parent_id')->toArray();
            $v['replies'] = $re_r[$v['rid']];
        }*/
        foreach ($mes as $k => $value) {
            $value['name'] = $user;
            $value['replies'] = $reply;
        }
        $message_info['message'] = $mes;
        return $message_info;
    }

    public function getOpenAll(array $request)
    {
        $result = array();
        $result['status'] = 0;
        $reply = ReplyModel::find($request['rid']);
        if ($reply->count() == 0) {
            return [];
        } else {
            $reply = $reply->toArray();
        }
        /*$mes_id = collect($reply)->where('parent_id', 0)->pluck('message_id');dd($mes_id);
        $reply = ReplyModel::where('message_id', $request['id']);
        //dd($reply->message_id);
        //$mes_id = collect($reply)->message_id;
        $id = intval($request['id']);
        if ($id != $mes_id) {
            $result['msg'] = "请输入该条留言下正确的回复ID!";
        } else {*/
        $user = UserModel::find($reply['user_id']);
        if ($user->count() == 0) {
            return [];
        } else {
            $user = $user->name;
        }
        $data = ReplyModel::where('message_id', $request['id'])->latest()->get()->toArray();
        $index = array();
        $rs = array();
        foreach ($data as &$r) {
            $index[$r['rid']] = &$r;
            $r['replies'] = [];
            if ($r['parent_id'] == $request['rid']) {
                $rs[] = &$r;
            } else {
                $index[$r['parent_id']]['replies'][] = &$r;
            }
        }
        $reply_info['reply'] = $reply;
        $reply_info['reply']['name'] = $user;
        $reply_info['reply']['replies'] = $rs;
        $result['status'] = 1;
        $result['msg'] = "open all info for one reply";
        $result['data'] = $reply_info;
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

    public function exception($message, int $code = 500)
    {
        throw new BaseExceptions($message, $code);
    }
}