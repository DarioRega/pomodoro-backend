<?php

namespace Tests\Feature\Sessions;

use App\Actions\Pomodoro\Sessions\AbortSession;
use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\SessionStatus;
use App\Models\PomodoroSession;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class SessionStatusTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    public function testSessionPending()
    {
        $session = $this->createSessionWithSteps();
        $this->assertEquals(SessionStatus::PENDING, $session->status);
    }

    public function testSessionInProgress()
    {
        $this->createInProgressStep();
        $session = PomodoroSession::currentByUser(Auth::user());
        $this->assertEquals(SessionStatus::IN_PROGRESS, $session->status);
    }

    public function testSessionPaused()
    {
        $this->createPausedStep();
        $session = PomodoroSession::currentByUser(Auth::user());
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
        AbortSession::run($session);
        $session = PomodoroSession::byUser(Auth::user())->first();
        $this->assertEquals(SessionStatus::ABORTED, $session->fresh()->status);
    }
}
