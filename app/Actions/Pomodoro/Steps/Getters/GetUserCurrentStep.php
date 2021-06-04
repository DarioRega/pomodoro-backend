<?php

namespace App\Actions\Pomodoro\Steps\Getters;

use App\Enums\StepStatus;
use App\Models\Step;
use App\Models\User;
use Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentStep
{
    use AsAction;

    public function handle(User $user): ?Step
    {
        $steps = GetUserCurrentSessionSteps::run($user);

        $stepInProgress = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::IN_PROGRESS;
        })->first();

        $stepPaused = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::PAUSED;
        })->first();

        $nextPendingStep = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::PENDING;
        })->first();

        if ($stepInProgress !== null) {
            return $stepInProgress;
        }

        if ($stepPaused !== null) {
            return $stepPaused;
        }

        if ($nextPendingStep !== null) {
            return $nextPendingStep;
        }

        return null;
    }

    public function asController(): ?Step
    {
        return $this->handle(Auth::user());
    }
}
