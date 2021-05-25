<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
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

    public function testCannotStartStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO testCannotStartStepDone');

        $this->expectException(InvalidStepActionException::class);
        $this->expectErrorMessage(__('Cannot stop a finished step'));
    }

    public function testCannotStartStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO testCannotStartStepSkipped');

        $this->expectException(InvalidStepActionException::class);
        $this->expectErrorMessage(__('Cannot stop a skipped step'));
    }
}
