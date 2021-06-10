<?php


namespace Tests\Feature;

use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\SkipStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Models\PomodoroSession;
use App\Models\Step;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait SessionsAndSteps
{
    public function createSession(array $data = []): PomodoroSession
    {
        Sanctum::actingAs($user = User::factory()->create());
        CreateDefaultSession::run($data);
        return $user->fresh()->pomodoroSessions->first();
    }

    public function createSessionWithSteps(): PomodoroSession
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        return $session->fresh();
    }

    public function createFinishedSession(): PomodoroSession
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        foreach ($session->fresh()->steps as $step) {
            StartStep::run($step);
            FinishStep::run($step);
        }
        return $session->fresh();
    }

    public function createPendingStep(): Step
    {
        $session = $this->createSessionWithSteps();
        return $this->getFirstSessionStep($session);
    }

    public function createInProgressStep(): Step
    {
        $step = $this->createPendingStep();
        return StartStep::run($step);
    }

    public function createSkippedStep(): Step
    {
        $step = $this->createPendingStep();
        return SkipStep::run($step);
    }

    public function createPausedStep(): Step
    {
        $step = $this->createInProgressStep();
        return PauseStep::run($step, '00:05:00');
    }

    public function createDoneStep(): Step
    {
        $step = $this->createInProgressStep();

        $step->resting_time = '00:00:00';
        $step->save();

        return FinishStep::run($step);
    }

    public function getFirstSessionStep($session): Step
    {
        return $session->fresh()->steps()->first();
    }
}
