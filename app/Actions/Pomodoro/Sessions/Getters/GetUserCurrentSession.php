<?php

namespace App\Actions\Pomodoro\Sessions\Getters;

use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Enums\SessionStatus;
use App\Enums\StepStatus;
use App\Models\PomodoroSession;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentSession
{
    use AsAction;

    public function handle(User $user): ?PomodoroSession
    {
        $sessions = PomodoroSession::byUser($user)->get();

        $session = $sessions->filter(function (PomodoroSession $session) {
            $isInProgress = $session->status == SessionStatus::IN_PROGRESS;
            $isPaused = $session->status == SessionStatus::PAUSED;
            return $isInProgress || $isPaused ;
        })->first();

        if ($session !== null) {
            $this->checkIfStepIsFinish($session->current_step);
        }

        return $session->fresh();
    }

    private function checkIfStepIsFinish(Step $currentStep)
    {
        if (StepStatus::IN_PROGRESS()->is($currentStep->status)) {
            if ($currentStep->end_time <= now()) {
                FinishStep::run($currentStep);
            }
        }
    }

    public function asController(): PomodoroSession|Response
    {
        $session = $this->handle(\Auth::user());

        if ($session === null) {
            return response()->noContent();
        }
        return $session;
    }
}
