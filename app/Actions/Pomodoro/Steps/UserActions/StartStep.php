<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class StartStep
{
    use AsAction;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(Step $step): Step
    {
        $this->validate($step);
        $step->started_at = now();
        LogAction::run($step, StepAction::START());
        return $step;
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate(Step $step)
    {
        if (StepStatus::IN_PROGRESS()->is($step->status)) {
            throw new InvalidStepActionException(__('Action already started'));
        }
    }
}
