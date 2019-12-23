<?php


namespace App\Http\Services\DataService;


use App\Models\MessageModel;
use App\Models\ReplyModel;
use Illuminate\Support\Facades\DB;

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
        $result = array();
        try {
            $message = MessageModel::find($id);
            if (is_null($message)) {
                throw new \Exception("The message not exist!", 0);
            } else {
                $mes_up = $message->update($request);
                if (!$mes_up) {
                    throw new \Exception("update message failed!", 0);
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "update message success！";
                    $result['data'] = $mes_up;
                    return $result;
                }
            }
        } catch (\Exception $exception) {
            echo "message:".$exception->getMessage()."\t";
            echo "code:".$exception->getCode();
        }
    }

    public function messageDelete(int $id)
    {
        $message = MessageModel::find($id);
        //MessageModel::withTrashed()->where('id', $id)->restore();die;
        $result = array();
        $result['status'] = 0;
        if (!is_null($message)) {
            DB::beginTransaction();
            try {
                $res = $message->delete();
                if (!$res->trashed()) {
                    throw new \Exception("delete message failed!", 0);
                }
                /*else {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                }*/
                $reply = ReplyModel::where('message_id', $id);
                //dd($reply->count());
                if ($reply->count() > 0) {
                    $res_reply = $reply->delete();
                    if (!$res_reply->trashed()) {
                        throw new \Exception("delete message's reply failed!", 0);
                    }
                }
                $result['status'] = 1;
                $result['msg'] = "delete message success!";
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                echo $exception->getMessage();
                echo $exception->getCode();
            }
        } else {
            $result['msg'] = "The message not exist!";
        }
        return $result;
    }

}