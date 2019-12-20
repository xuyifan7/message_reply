<?php

namespace App\Listeners;

use App\Events\MessageClickEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageClickListener
{
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
        //
    }
}
