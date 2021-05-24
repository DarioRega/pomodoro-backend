<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStep
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(StepType $stepType, PomodoroSession $session): Model
    {
        if (StepType::POMODORO()->is($stepType)) {
            return CreatePomodoroStep::run($session);
        }

        if (StepType::SMALL_BREAK()->is($stepType)) {
            return CreateSmallBreakStep::run($session);
        }

        if (StepType::BIG_BREAK()->is($stepType)) {
            return CreateBigBreakStep::run($session);
        }

        throw new Exception("Invalid step type: $stepType");
    }
}
