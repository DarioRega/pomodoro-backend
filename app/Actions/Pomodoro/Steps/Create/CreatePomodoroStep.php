<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePomodoroStep
{
    use AsAction;

    public function handle(PomodoroSession $session)
    {
        $session->steps()->create([
            'type' => StepType::Pomodoro(),
            'duration' => $session->pomodoro_duration,
            'resting_time' => $session->pomodoro_duration,
        ]);

        return $session->steps()->latest()->first();
    }
}
