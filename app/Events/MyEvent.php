<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $description;
    public  $url_now;
    public  $notification_for;
    public  $notify_to;

    public function __construct($message,$description,$url_now,$notification_for,$notify_to=null)
    {
        $this->message = $message;
        $this->description = $description;
        $this->url_now = $url_now;
        $this->notification_for = $notification_for;
        $this->notify_to = $notify_to;


    }

    public function broadcastOn()
    {
        return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
