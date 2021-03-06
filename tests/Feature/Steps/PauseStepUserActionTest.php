<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\StepTime;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PauseStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndStepsCreator;
    use StepTime;

    public function testPauseStep()
    {
        $step = $this->createInProgressStep();

        $step = PauseStep::run($step, '00:07:29');

        $this->assertNotNull($step->started_at);
        $this->assertNull($step->end_time);
        $this->assertEquals('00:07:29', $step->resting_time);
        $this->assertEquals(StepStatus::PAUSED, $step->fresh()->status);
        $this->assertEquals(StepAction::PAUSE, $step->actions->last()->action);
    }

    public function testPauseStepWithoutRestingTime()
    {
        $stepInProgress = $this->createInProgressStep();

        $step = PauseStep::run($stepInProgress);

        $this->assertEquals('00:25:04', $step->resting_time);
        $this->assertNull($step->end_time);
    }

    public function testCannotPauseStepDone()
    {
        $step = $this->createDoneStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a finished step'));

        PauseStep::run($step, '00:05:00');
    }

    public function testCannotPauseStepSkipped()
    {
        $step = $this->createSkippedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a skipped step'));

        PauseStep::run($step, '00:05:00');
    }

    public function testCannotPauseStepPending()
    {
        $step = $this->createPendingStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a pending step'));

        PauseStep::run($step, '00:05:00');
    }

    public function testCannotPauseStepPaused()
    {
        $step = $this->createPausedStep();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step already paused'));

        PauseStep::run($step, '00:05:00');
    }
}
