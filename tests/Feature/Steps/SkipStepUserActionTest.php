<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\SkipStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkipStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    public function testSkipStep()
    {
        $step = $this->createPendingStep();

        $step = SkipStep::run($step);

        $this->assertNotNull($step->skipped_at);
        $this->assertEquals(StepStatus::SKIPPED, $step->status);
        $this->assertEquals(StepAction::SKIP, $step->actions->last()->action);
    }

    public function testCannotSkipStepInProgress()
    {
        $step = $this->createInProgressStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot skip a step in progress'));

        SkipStep::run($step);
    }

    public function testCannotSkipStepSkipped()
    {
        $step = $this->createSkippedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step is already skipped'));

        SkipStep::run($step);
    }

    public function testCannotSkipStepFinished()
    {
        $step = $this->createDoneStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot skip a finished step'));

        SkipStep::run($step);
    }
}
