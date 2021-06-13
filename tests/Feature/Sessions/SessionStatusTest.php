<?php

namespace Tests\Feature\Sessions;

use App\Actions\Pomodoro\Sessions\AbortSession;
use App\Actions\Pomodoro\Sessions\StartSession;
use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\SessionStatus;
use App\Enums\StepStatus;
use App\Models\PomodoroSession;
use App\Models\Step;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\TestCase;

class SessionStatusTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndStepsCreator;

    public function testSessionPending()
    {
        $session = $this->createSessionWithSteps();
        $this->assertEquals(SessionStatus::PENDING, $session->status);
    }

    public function testSessionInProgress()
    {
        $this->createInProgressStep();
        $session = GetUserCurrentSession::run(Auth::user());
        $this->assertEquals(SessionStatus::IN_PROGRESS, $session->status);
    }

    public function testSessionPaused()
    {
        $this->createPausedStep();
        $session = GetUserCurrentSession::run(Auth::user());
        $this->assertEquals(SessionStatus::PAUSED, $session->status);
    }

    public function testSessionDone()
    {
        $session = $this->createSessionWithSteps();
        foreach ($session->fresh()->steps as $step) {
            StartStep::run($step);
            FinishStep::run($step);
        }
        $session = PomodoroSession::byUser(Auth::user())->first();
        $this->assertEquals(SessionStatus::DONE, $session->fresh()->status);
    }

    public function testSessionAborted()
    {
        $session = $this->createSessionWithSteps();
        StartSession::run($session);
        $session = PomodoroSession::byUser(Auth::user())->first();
        AbortSession::run($session);
        $this->assertEquals(SessionStatus::ABORTED, $session->fresh()->status);
    }


    public function testSessionMoveToFinishWhenTimeIsPast()
    {
        $session = $this->createSessionWithSteps();
        StartSession::run($session);
        $step = $session->steps->first();
        $step->end_time = Carbon::yesterday();
        $step->save();

        $currentStep = GetUserCurrentStep::run(Auth::user());
        $this->assertEquals($currentStep->status, StepStatus::PENDING());
    }
}
