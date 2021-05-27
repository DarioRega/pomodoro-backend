<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class PauseStep
{
    use AsAction;
    private Step $step;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(Step $step): Step
    {
        $this->step = $step;
        $this->validate();
        LogAction::run($step, StepAction::PAUSE());
        return $step;
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate()
    {
        $status = $this->step->status;

        if (StepStatus::PENDING()->is($status)) {
            throw new InvalidStepActionException(__('Cannot pause a pending step'));
        }

        if (StepStatus::PAUSED()->is($status)) {
            throw new InvalidStepActionException(__('Step already paused'));
        }

        if (StepStatus::SKIPPED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot pause a skipped step'));
        }

        if (StepStatus::DONE()->is($status)) {
            throw new InvalidStepActionException(__('Cannot pause a finished step'));
        }
    }
}
