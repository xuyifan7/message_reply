<?php


namespace App\Http\Services\DataService;


use App\Models\MessageModel;

class MessageDS
{
    public function messageCreate(array $request)
    {
        $message = new MessageModel;
        $mes_data = $message->create($request);
        return $mes_data->toArray();
    }

    public function messageUpdate(array $request, int $id)
    {
        $message = MessageModel::find($id);
        $result = array();
        $result['status'] = 0;
        $mes_up = $message->update($request);
        if ($mes_up) {
            $result['status'] = 1;
            $result['msg'] = "update reply success！";
            $result['data'] = $mes_up;
        }
        return $result;
    }

    public function messageDelete(int $id)
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
    }

}