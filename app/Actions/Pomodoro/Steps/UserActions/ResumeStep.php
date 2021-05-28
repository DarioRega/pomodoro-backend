<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\StepTime;
use App\Actions\Pomodoro\Steps\LogAction;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class ResumeStep
{
    use AsAction;
    use StepTime;

    private Step $step;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(Step $step): Step
    {

        $this->step = $step->fresh();
        $this->validate();
        LogAction::run($step, StepAction::RESUME());
        $this->calculateStepEndTime();

        return $this->step->fresh();
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate()
    {
        $status = $this->step->status;

        if (StepStatus::PENDING()->is($status)) {
            throw new InvalidStepActionException(__('The step need to be paused'));
        }

        if (StepStatus::IN_PROGRESS()->is($status)) {
            throw new InvalidStepActionException(__('Cannot resume a step in progress'));
        }

        if (StepStatus::SKIPPED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot resume a skipped step'));
        }

        if (StepStatus::DONE()->is($status)) {
            throw new InvalidStepActionException(__('Cannot resume a finished step'));
        }
    }
}
