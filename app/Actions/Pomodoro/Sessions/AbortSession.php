<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Models\PomodoroSession;
use Lorisleiva\Actions\Concerns\AsAction;

class AbortSession
{
    use AsAction;

    public function handle(PomodoroSession $session)
    {
        $session->aborted_at = now();
        $session->save();
    }
}
