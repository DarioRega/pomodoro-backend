<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class StartStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    public function testStartStep()
    {
        $step = $this->createPendingStep();

        $step = StartStep::run($step);
        $action = $step->actions->last();

        $this->assertNotNull($step->started_at);
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
        $this->assertEquals(StepAction::START, $action->action);
    }

    public function testCannotStartStepInProgress()
    {
        $step = $this->createInProgressStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step already started'));
        StartStep::run($step);
    }

    public function testCannotStartStepPaused()
    {
        $step = $this->createPausedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot restart a paused step'));

        StartStep::run($step);
    }

    public function testCannotStartStepDone()
    {
        $step = $this->createDoneStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot restart a finished step'));

        StartStep::run($step);
    }

    public function testCannotStartStepSkipped()
    {
        $step = $this->createSkippedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot restart a skipped step'));

        StartStep::run($step);
    }
}
