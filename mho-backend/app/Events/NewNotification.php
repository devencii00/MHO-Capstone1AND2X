<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;

class NewNotification implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public $userId,
        public $notification
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.new';
    }
}
