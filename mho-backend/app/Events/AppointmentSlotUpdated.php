<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;

class AppointmentSlotUpdated implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets;

    public $queue = 'default';

    public function __construct(
        public $departmentId,
        public $slotData
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('appointments.' . $this->departmentId),
            new PrivateChannel('appointments.all'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'appointment.updated';
    }
}
