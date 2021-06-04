<?php

namespace App\Actions\Pomodoro\Sessions\Getters;

use App\Enums\SessionStatus;
use App\Models\PomodoroSession;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentSession
{
    use AsAction;

    public function handle(User $user): ?PomodoroSession
    {
        $sessions = PomodoroSession::byUser($user)->get();
        return $sessions->filter(function (PomodoroSession $session) {
            $isInProgress = $session->status == SessionStatus::IN_PROGRESS;
            $isPaused = $session->status == SessionStatus::PAUSED;
            return $isInProgress || $isPaused ;
        })->first();
    }

    public function asController(): ?PomodoroSession
    {
        return $this->handle(\Auth::user());
    }
}
