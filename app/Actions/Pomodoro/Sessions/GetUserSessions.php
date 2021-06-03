<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Models\PomodoroSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserSessions
{
    use AsAction;

    public function handle(User $user): Collection
    {
        return PomodoroSession::whereUserId($user->id)
            ->with(['steps', 'steps.actions'])
            ->get();
    }

    public function asController(): Collection
    {
        return $this->handle(\Auth::user());
    }
}
