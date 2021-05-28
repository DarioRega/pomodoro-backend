<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\StepTime;
use App\Actions\Pomodoro\Steps\LogAction;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class StartStep
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

        $step->started_at = now();
        $step->save();

        $this->calculateStepEndTime();
        LogAction::run($step, StepAction::START());

        return $this->step->fresh();
    }

    /**
     * @throws InvalidStepActionException
     */
    private function validate()
    {
        $status = $this->step->status;

        if (StepStatus::IN_PROGRESS()->is($status)) {
            throw new InvalidStepActionException(__('Step already started'));
        }

        if (StepStatus::PAUSED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot restart a paused step'));
        }

        if (StepStatus::SKIPPED()->is($status)) {
            throw new InvalidStepActionException(__('Cannot restart a skipped step'));
        }

        if (StepStatus::DONE()->is($status)) {
            throw new InvalidStepActionException(__('Cannot restart a finished step'));
        }
    }
}
