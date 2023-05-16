<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chat_id;

    public function __construct(Message $message) {
        $this->message = $message;
        $this->chat_id = $message->chat_id;
    }

    public function broadcastOn() {
        return new PrivateChannel("chat.{$this->chat_id}");
    }

    public function broadcastWith() {
        return [
            'message' => $this->message,
        ];
    }

    public function broadcastAs() {
        return 'message.sent';
    }


}
