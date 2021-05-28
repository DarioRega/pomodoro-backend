<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\StepTime;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class FinishStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use StepTime;
    use SessionsAndSteps;

    public function testFinishStep()
    {
        $step = $this->createInProgressStep();

        $step->resting_time = '00:00:00';
        $step->save();

        $step = FinishStep::run($step);

        $action = $step->actions->last();

        $this->assertNotNull($step->finished_at);
        $this->assertNotNull($step->started_at);
        $this->assertNull($step->skipped_at);
        $this->assertEquals(StepStatus::DONE, $step->status);
        $this->assertEquals('00:00:00', $step->resting_time);
        $this->assertEquals(StepAction::FINISH, $action->action);
    }

    public function testCannotFinishStepPending()
    {
        $step = $this->createPendingStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot finish a pending step'));

        FinishStep::run($step);
    }

    public function testCannotFinishStepSkipped()
    {
        $step = $this->createSkippedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot finish a skipped step'));

        FinishStep::run($step);
    }


    public function testCannotFinishStepPaused()
    {
        $step = $this->createPausedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot finish a paused step'));

        FinishStep::run($step);
    }

    public function testCannotFinishStepDone()
    {
        $step = $this->createDoneStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step is already done'));

        FinishStep::run($step);
    }

    public function testCannotFinishStepWithRemainingRestingTime()
    {
        $step = $this->createInProgressStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Resting time must be 00:00:00 to finish a step'));

        FinishStep::run($step);
    }
}
