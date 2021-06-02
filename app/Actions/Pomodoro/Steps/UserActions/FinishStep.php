<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Actions\Pomodoro\StepTime;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class FinishStep
{
    use AsAction;
    use StepTime;

    private Step $step;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(Step $step): Step
    {
        $this->step = $step;
        $this->validate();
        LogAction::run($step, StepAction::FINISH());
        $this->unsetEndTime();

        $this->step->finished_at = now();
        $this->step->save();

        return $this->step->fresh();
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate()
    {
        $this->validateStatus();

        if ($this->step->resting_time !== '00:00:00') {
            throw new InvalidStepActionException(__('Resting time must be 00:00:00 to finish a step'));
        }
    }

    private function validateStatus()
    {
        $status = $this->step->status;

        if (StepStatus::PENDING()->is($status)) {
            throw new InvalidStepActionException(__('Cannot finish a pending step'));
        }

        if (StepStatus::SKIPPED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot finish a skipped step'));
        }

        if (StepStatus::PAUSED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot finish a paused step'));
        }

        if (StepStatus::DONE()->is($status)) {
            throw new InvalidStepActionException(__('Step is already done'));
        }
    }
}
