<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepsUserActionsTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;

    public function testStartStep()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        $step = StartStep::run($step);
        $action = $step->actions()->first();

        $this->assertNotNull($step->started_at);
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
        $this->assertEquals(StepAction::START(), $action->action);
    }

    public function testCannotStartStepInProgress()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        $step = StartStep::run($step);

        $this->expectException(InvalidStepActionException::class);
        $this->expectErrorMessage(__('Action already started'));
        StartStep::run($step);
    }

    public function testCannotStartStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO testCannotStartStepDone');

        $this->expectException(InvalidStepActionException::class);
        $this->expectErrorMessage(__('Cannot restart a finished step'));
    }

    public function testCannotStartStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO testCannotStartStepSkipped');

        $this->expectException(InvalidStepActionException::class);
        $this->expectErrorMessage(__('Cannot restart a skipped step'));
    }

    public function testPauseStep()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        $step = StartStep::run($step);

        $this->assertNotNull($step->started_at);
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
    }
}
