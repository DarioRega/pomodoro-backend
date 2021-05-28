<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\UserActions\FinishStep;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\SkipStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\Sessions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkipStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;

    public function testSkipStep()
    {
        $session = $this->createSessionWithSteps();
        $step = $this->getFirstSessionStep($session);
        $step = StartStep::run($step);
        $step = PauseStep::run($step);
        $step = SkipStep::run($step);

        $this->assertNotNull($step->skipped_at);
        $this->assertEquals(StepStatus::SKIPPED, $step->status);
        $this->assertEquals(StepAction::SKIP, $step->actions->last()->action);
    }

    public function testCannotSkipStepInProgress()
    {
        $session = $this->createSessionWithSteps();
        $step = $this->getFirstSessionStep($session);
        $step = StartStep::run($step);

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot skip a step in progress'));

        SkipStep::run($step);
    }

    public function testCannotSkipStepSkipped()
    {
        $session = $this->createSessionWithSteps();
        $step = $this->getFirstSessionStep($session);
        $step = SkipStep::run($step);

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Step is already skipped'));

        SkipStep::run($step);
    }

    public function testCannotSkipStepFinished()
    {
        $this->markTestSkipped('Todo');

        $session = $this->createSessionWithSteps();
        $step = $this->getFirstSessionStep($session);
        $step = StartStep::run($step);
        $step = FinishStep::run($step);

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot skip a finished step'));

        SkipStep::run($step);
    }
}
