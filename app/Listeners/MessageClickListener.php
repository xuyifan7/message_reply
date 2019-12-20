<?php

namespace App\Listeners;

use App\Events\MessageClickEvent;
use App\Models\MessageModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageClickListener
{
    const idExpireSec = 200;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageClickEvent  $event
     * @return void
     */
    public function handle(MessageClickEvent $event)
    {
        $message = $event->message;
        $id = $event->id;
        if ($this->idClickLimit($id)) {
            $this->updateCacheClickCount($id);
        }
    }

    public function idClickLimit($id)
    {
        $idMessageClickKey = 'message:id:'.$id;
        $existsInRedisSet = Redis::command('SISMEMBER', [$idMessageClickKey, $id]); //如果集合中不存在这个建 那么新建一个并设置过期时间
        if(!$existsInRedisSet){
            Redis::command('SADD', [$idMessageClickKey, $id]);
            //并给该键设置生命时间,这里设置300秒,300秒后同一IP访问就当做是新的浏览量了
            Redis::command('EXPIRE', [$idMessageClickKey, self::ipExpireSec]);
            return true;
        }
        return false;
    }

    public function updateModelClickCount($id, $count)
    {
        $message = MessageModel::find($id);
        $message->click_count += $count;
        $message->save();
    }

    public function updateCacheClickCount($id)
    {
        $cacheKey = 'message:click:'.$id;
        if(Redis::command('HEXISTS', [$cacheKey, $id])){
            $save_count = Redis::command('HINCRBY', [$cacheKey, $id, 1]);
            if($save_count == self::idClickLimit){
                $this->updateModelClickCount($id, $save_count);
                Redis::command('HDEL', [$cacheKey, $id]);
                Redis::command('DEL', ['laravel:post:cache:'.$id]);
            }
        }else{
            Redis::command('HSET', [$cacheKey, $id, '1']);
        }
    }
}
