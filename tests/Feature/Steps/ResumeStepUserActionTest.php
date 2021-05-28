<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\ResumeStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\Sessions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResumeStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;

    public function testResumeStep()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $this->getFirstSessionStep($session);

        StartStep::run($step->fresh());
        PauseStep::run($step->fresh());
        ResumeStep::run($step->fresh());

        $step = $step->fresh();
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->fresh()->status);
        $this->assertEquals(StepAction::RESUME, $step->actions->last()->action);
    }

    public function testCannotResumeStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO testCannotStartStepDone');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot stop a finished step'));
    }

    public function testCannotResumeStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot stop a skipped step'));
    }

    public function testCannotResumeStepInProgress()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $this->getFirstSessionStep($session);

        StartStep::run($step->fresh());

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot resume a step in progress'));

        ResumeStep::run($step->fresh());
    }

    public function testCannotResumeStepPending()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $this->getFirstSessionStep($session);

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('The step need to be paused'));

        ResumeStep::run($step->fresh());
    }
}
