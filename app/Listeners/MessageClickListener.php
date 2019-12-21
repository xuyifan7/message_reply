<?php

namespace App\Listeners;

use App\Events\MessageClickEvent;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Cache;


class MessageClickListener
{
    const MESSAGECLICKMAX = 20;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  MessageClickEvent $event
     * @return void
     */
    public function handle(MessageClickEvent $event)
    {
        $message = $event->message;
        $id = $message->id;
        $ip = $event->ip;
        $this->updateCacheClickCount($id, $ip);
    }

    public function updateModelClickCount($id, $count)
    {
        $message = MessageModel::find($id);
        $message->click_count += $count;
        $message->save();
    }

    public function updateCacheClickCount($id)
    {
        $cacheKey = 'message:click:' . $id;
        if (Cache::has($cacheKey)) {
            $value = Cache::get($cacheKey) + 1;
            $save_count = Cache::put($cacheKey, $value);
            if ($save_count == self::MESSAGECLICKMAX) {
                $this->updateModelClickCount($id, $save_count);
                Cache::forget($cacheKey);
            } else {
                Cache::set($cacheKey, 1);
            }
        }
    }
}
