<?php

namespace App\Events;

use App\Models\MessageModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageClickEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MessageModel $message, $id)
    {
        $this->message = $message;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
