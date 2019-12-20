<?php


namespace App\Http\Services\DataService;

use App\Models\MessageModel;
use Illuminate\Support\Facades\Cache;

class MessageStorageDS
{
    public function putById($id)
    {
        $minutes = 1;
        $data = MessageModel::where('id', $id)->get();
        Cache::put('message:'.$id, $data, $minutes);
    }

    public function getById($id)
    {
        $data = Cache::get('message:'.$id);
        return $data;
    }
}