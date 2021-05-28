<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\UserActions\ResumeStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResumeStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    public function testResumeStep()
    {
        $step = $this->createPausedStep();

        $step = ResumeStep::run($step);

        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
        $this->assertEquals(StepAction::RESUME, $step->actions->last()->action);
    }

    public function testCannotResumeStepPending()
    {
        $step = $this->createPendingStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('The step need to be paused'));

        ResumeStep::run($step);
    }

    public function testCannotResumeStepInProgress()
    {
        $step = $this->createInProgressStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot resume a step in progress'));

        ResumeStep::run($step);
    }

    public function testCannotResumeStepSkipped()
    {
        $step = $this->createSkippedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot resume a skipped step'));

        ResumeStep::run($step);
    }

    public function testCannotResumeStepDone()
    {
        $step = $this->createDoneStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot resume a finished step'));

        ResumeStep::run($step);
    }
}
