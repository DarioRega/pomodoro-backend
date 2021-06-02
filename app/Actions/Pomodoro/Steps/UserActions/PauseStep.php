<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Actions\Pomodoro\StepTime;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use Lorisleiva\Actions\Concerns\AsAction;

class PauseStep
{
    use AsAction;
    use StepTime;

    private Step $step;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(Step $step, string $resting_time): Step
    {
        $this->step = $step->fresh();
        $this->validate();

        $this->step->resting_time = $resting_time;
        $this->step->save();

        LogAction::run($step, StepAction::PAUSE());
        $this->unsetEndTime();

        return $this->step->fresh();
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
