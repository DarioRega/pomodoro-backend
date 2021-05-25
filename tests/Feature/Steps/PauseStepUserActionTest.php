<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\Sessions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PauseStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;

    public function testPauseStep()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        StartStep::run($step);
        PauseStep::run($step);

        $step = $step->fresh();
        $this->assertNotNull($step->started_at);
        $this->assertEquals(StepStatus::PAUSED, $step->fresh()->status);
        $this->assertEquals(StepAction::PAUSE, $step->actions->last()->action);
    }

    public function testCannotPauseStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot stop a finished step'));
    }

    public function testCannotPauseStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot stop a skipped step'));
    }

    public function testCannotPauseStepPending()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot stop a pending step'));

        PauseStep::run($step);
    }
}
