<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;

class QueueUpdated implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets;

    public $queue = 'default';

    public function __construct(
        public ?int $doctorId,
        public ?array $queueData = null
    ) {
        //
    }

    public function broadcastWith(): array
    {
        return [
            'doctor_id' => $this->doctorId,
            'queue_data' => $this->queueData,
            'fired_at' => (int) round(microtime(true) * 1000),
        ];
    }

    public function broadcastOn(): array
    {
        $channels = [
            new Channel('queue.display'),
            new PrivateChannel('queue.all'),
        ];

        if ($this->doctorId) {
            $channels[] = new PrivateChannel('queue.'.$this->doctorId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'queue.updated';
    }
}
