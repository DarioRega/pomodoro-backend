<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Enums\StepAction;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class StartStep
{
    use AsAction;

    public function handle(Step $step): Step
    {
        $step->started_at = now();
        LogAction::run($step, StepAction::START());
        return $step;
    }
}
