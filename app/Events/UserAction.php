<?php

namespace App\Events;

use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
use App\Models\PomodoroSession;
use App\Models\Step;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PomodoroSession $session;
    public Step $currentStep;
    public User $user;

    public bool $afterCommit = true;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, PomodoroSession $session)
    {
        $this->user = $user;
        $this->session = $session;
        $this->currentStep = GetUserCurrentStep::run($user);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'current.session';
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
