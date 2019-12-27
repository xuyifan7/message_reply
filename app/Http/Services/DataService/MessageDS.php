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

    public function getById($id)
    {
        return MessageModel::find($id);
    }

    public function update($message, $data)
    {
        return $message->update($data);
    }

    public function delete($data)
    {
        return $data->delete();
    }

    public function getReply($id)
    {
        return ReplyModel::where('message_id', $id);
    }


}