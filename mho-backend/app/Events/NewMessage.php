<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $queue = 'high';

    public function __construct(
        public $senderId,
        public $receiverId,
        public $message
    ) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('messages.' . $this->receiverId),
        ];

        // Also notify the sender so their conversation list updates
        if ($this->senderId !== $this->receiverId) {
            $channels[] = new PrivateChannel('messages.' . $this->senderId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.new';
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'conversation_with' => $this->senderId,
            'message_id' => $this->message->message_id ?? null,
            'timestamp' => now()->toISOString(),
        ];
    }
}
