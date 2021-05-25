<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Sessions;
use Tests\TestCase;

class StartStepUserActionTest extends TestCase
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
        $this->assertEquals(StepAction::START, $action->action);
    }

    public function testCannotStartStepInProgress()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        $step = StartStep::run($step);

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step already started'));
        StartStep::run($step);
    }

    public function testCannotStartStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot restart a skipped step'));
    }

    public function testCannotStartStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot restart a finished step'));
    }
}
