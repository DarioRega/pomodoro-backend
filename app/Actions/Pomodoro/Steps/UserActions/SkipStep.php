<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Actions\Pomodoro\StepTime;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class SkipStep
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
        LogAction::run($step, StepAction::SKIP());
        $this->unsetEndTime();

        $this->step->skipped_at = now();
        $this->step->save();

        return $this->step->fresh();
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate()
    {
        $status = $this->step->status;

        if (StepStatus::IN_PROGRESS()->is($status)) {
            throw new InvalidStepActionException(__('Cannot skip a step in progress'));
        }

        if (StepStatus::DONE()->is($status)) {
            throw new InvalidStepActionException(__('Cannot skip a finished step'));
        }

        if (StepStatus::SKIPPED()->is($status)) {
            throw new InvalidStepActionException(__('Step is already skipped'));
        }
    }
}
