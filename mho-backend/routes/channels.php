<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('appointments.{departmentId}', function ($user) {
    return $user !== null;
});

Broadcast::channel('queue.{departmentId}', function ($user) {
    return $user !== null;
});

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat.{conversationId}', function ($user) {
    return $user !== null;
});

Broadcast::channel('appointments.all', function ($user) {
    return $user !== null;
});

Broadcast::channel('queue.all', function ($user) {
    return $user !== null;
});
