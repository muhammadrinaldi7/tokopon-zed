<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $user;
    public string $message;
    public string $time;
    public int $userId;
    public int $conversationId;

    public function __construct(string $user, string $message, string $time, int $userId, int $conversationId)
    {
        $this->user = $user;
        $this->message = $message;
        $this->time = $time;
        $this->userId = $userId;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'time' => $this->time,
            'userId' => $this->userId,
            'conversationId' => $this->conversationId,
        ];
    }
}
