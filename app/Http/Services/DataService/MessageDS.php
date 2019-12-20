<?php


namespace App\Http\Services\DataService;


use App\Models\MessageModel;
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
        $result['status'] = 0;
        DB::beginTransaction();
        try {
            $message = MessageModel::find($id);
            $mes_up = $message->update($request);
            if (!$mes_up) {
                throw new \Exception("update message failed!", 0);
            } else {
                $result['status'] = 1;
                $result['msg'] = "update message success！";
                $result['data'] = $mes_up;
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            echo $exception->getMessage();
            echo $exception->getCode();
        }
        return $result;
    }

    public function messageDelete(int $id)
    {
        $message = MessageModel::find($id);
        $result = array();
        $result['status'] = 0;
        if (!is_null($message)) {
            DB::beginTransaction();
            try {
                $res = $message->delete();
                if (!$res) {
                    throw new \Exception("delete message failed!", 0);
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "delete message success!";
                }
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