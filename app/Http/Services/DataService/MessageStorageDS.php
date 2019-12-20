<?php


namespace App\Http\Services\DataService;

use App\Models\MessageModel;
use Illuminate\Support\Facades\Cache;

class MessageStorageDS
{
    public function put($id)
    {
        $minutes = 1;
        $data = MessageModel::where('id', $id)->get();
        Cache::put('message:'.$id, $data, $minutes);
    }

    public function get($id)
    {
        $data = Cache::get('message:'.$id);
        return $data;
    }
}