<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublisherEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $description;
    public  $url_now;

    public function __construct($message,$description,$url_now)
    {
        $this->message = $message;
        $this->description = $description;
        $this->url_now = $url_now;

    }

    public function broadcastOn()
    {
        return ['publisher-channel'];
    }

    public function broadcastAs()
    {
        return 'publisher-event';
    }
}
