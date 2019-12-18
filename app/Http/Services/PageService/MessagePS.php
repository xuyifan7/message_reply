<?php


namespace App\Http\Services\PageService;


use App\Models\MessageModel;
use App\Models\ReplyModel;
use App\Models\UserModel;
use Illuminate\Pagination\LengthAwarePaginator;

class MessagePS
{
    public function getMessageList()
    {
        $message = MessageModel::latest()->paginate(5);
        $messages = MessageModel::latest()->get();
        $user_ids = collect($messages)->pluck('user_id')->unique()->toArray();
        $user = UserModel::whereIn('uid', $user_ids)->get()->toArray();
        $user = collect($user)->pluck('uid', 'name')->toArray();
        foreach ($message as $k => $v) {
            $count = ReplyModel::where('message_id', $v['id'])->count();
            $v['count'] = $count;
            foreach ($user as $name => $users) {
                if ($v['user_id'] == $users) {
                    $v['name'] = $name;
                }
            }
        }
        return $message;
    }

    //show one message's all replies and paginate
    public function getInfo(array $request, $current_url)
    {
        $message_info['message'] = MessageModel::where('id', $request['id'])->get();
        $user = UserModel::find(MessageModel::find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        //$replies = $this->replyList($data, 0);
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
    public function getOneInfo(array $request, $current_url)
    {
        $message_info['message'] = MessageModel::where('id', $request['id'])->get();
        $user = UserModel::find(MessageModel::find($request['id'])->user_id)->name;
        $data = ReplyModel::where('message_id', $request['id'])->oldest()->get();
        $reply = ReplyModel::where('message_id', $request['id'])->where('reply_id', 0)->oldest()->paginate(2);
        $reply->withPath($current_url);
        foreach ($reply as $k => $v) {
            $count = ReplyModel::where('reply_id', $v['rid'])->count();
            $v['count'] = $count;
            $re_r = collect($data)->whereIn('reply_id', $v['rid'])->first()->toArray();
            /*//show two
            $re = collect($data)->whereIn('reply_id', $v['rid'])->toArray();
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

    /*public function replyList($data, $reply_id)
    {
        $result = array();
        foreach ($data as $k => $v) {
            if (empty($data)) {
                return '';
            }
            if ($v['reply_id'] == $reply_id) {
                $result[$k] = $v;
                unset($data[$k]);
                $result[$k]['replies'] = $this->replyList($data, $v['rid']);
            }
        }
        return $result;
    }*/
}