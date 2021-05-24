<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStep
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(StepType $stepType, PomodoroSession $session)
    {
        if (StepType::Pomodoro()->is($stepType)) {
            return CreatePomodoroStep::run($session);
        }

        throw new Exception("Invalid step type: $stepType");
    }
}
