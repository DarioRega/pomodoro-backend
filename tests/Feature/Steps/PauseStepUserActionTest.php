<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\CalculateTime;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepAction;
use App\Enums\StepStatus;
use App\Exceptions\InvalidStepActionException;
use Tests\Feature\Sessions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PauseStepUserActionTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;
    use CalculateTime;

    public function testPauseStep()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        StartStep::run($step);
        PauseStep::run($step->fresh());

        $step = $step->fresh();
        $this->assertNotNull($step->started_at);
        $this->assertNotNull($step->end_time);
        $this->assertEquals(StepStatus::PAUSED, $step->fresh()->status);
        $this->assertEquals(StepAction::PAUSE, $step->actions->last()->action);
    }

    /**
     * @dataProvider restingTimeProvider
     */
    public function testPauseEndTimeStep(string $hour, string $min, string $sec)
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        StartStep::run($step);

        $step = $step->fresh();
        $step->resting_time = "$hour:$min:$sec";
        $step->save();

        PauseStep::run($step->fresh());

        $step = $step->fresh();

        $expectedEndTime = now()->addHours($hour)->addMinutes($min)->addSeconds($sec);
        $this->assertEquals(
            $expectedEndTime->format('H:i:s'),
            $this->createFromDateTime($step->end_time)->format('H:i:s')
        );
    }

    public function testCannotPauseStepDone()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a finished step'));
    }

    public function testCannotPauseStepSkipped()
    {
        // TODO
        $this->markTestSkipped('TODO');

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a skipped step'));
    }

    public function testCannotPauseStepPending()
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();

        $this->expectException(InvalidStepActionException::class);
        $this->expectExceptionMessage(__('Cannot pause a pending step'));

        PauseStep::run($step);
    }

    public function restingTimeProvider(): array
    {
        return [
            '20 minutes'                       => ["00", "20", "00"],
            '1 hour and 15 minutes'            => ["01", "15", "00"],
            '1 hour, 5 minutes and 30 seconds' => ["01", "05", "30"],
            '45 seconds'                       => ["00", "00", "00"],
            '1 hour and 15 seconds'            => ["01", "00", "15"],
        ];
    }
}
