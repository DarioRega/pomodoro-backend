<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSessionSteps
{
    use AsAction;

    public function handle(PomodoroSession $session)
    {
        foreach (range(1, $session->pomodoro_quantity) as $i) {
            CreateStep::run(StepType::POMODORO(), $session);
            if ($i === $session->pomodoro_quantity) {
                CreateStep::run(StepType::BIG_BREAK(), $session);
            } else {
                CreateStep::run(StepType::SMALL_BREAK(), $session);
            }
        }
    }
}
