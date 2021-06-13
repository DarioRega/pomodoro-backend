<?php

namespace App\Events\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public User $user;
    public string $action;
    public bool $afterCommit = true;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Task $task, string $action)
    {
        $this->user = $user;
        $this->task = $task;
        $this->action = $action;
    }

    public function broadcastWith(): array
    {
        return $this->task->toArray();
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "task.$this->action";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->user->id);
    }
}
