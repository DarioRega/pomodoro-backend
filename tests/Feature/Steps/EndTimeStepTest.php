<?php

namespace Tests\Feature\Steps;

use App\Actions\Pomodoro\StepTime;
use App\Actions\Pomodoro\Steps\UserActions\ResumeStep;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EndTimeStepTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndStepsCreator;
    use StepTime;

    /**
     * @dataProvider restingTimeProvider
     */
    public function testStartEndTimeIsCalculated(string $hour, string $min, string $sec)
    {
        $step = $this->createPendingStep();

        $step = $step->fresh();
        $step->resting_time = "$hour:$min:$sec";
        $step->save();

        StartStep::run($step);

        $expectedEndTime = now()->addHours($hour)->addMinutes($min)->addSeconds($sec);

        $step = $step->fresh();
        $this->assertEquals(
            $expectedEndTime->format('H:i:s'),
            $this->createFromDateTime($step->end_time)->format('H:i:s')
        );
    }

    /**
     * @dataProvider restingTimeProvider
     */
    public function testResumeEndTimeIsCalculated(string $hour, string $min, string $sec)
    {
        $step = $this->createPausedStep();

        $step = $step->fresh();
        $step->resting_time = "$hour:$min:$sec";
        $step->save();

        ResumeStep::run($step);

        $expectedEndTime = now()->addHours($hour)->addMinutes($min)->addSeconds($sec);

        $step = $step->fresh();
        $this->assertEquals(
            $expectedEndTime->format('H:i:s'),
            $this->createFromDateTime($step->end_time)->format('H:i:s')
        );
    }

    public function restingTimeProvider(): array
    {
        return [
            '20 minutes'            => ["00", "20", "00"],
            '1 hour and 15 minutes' => ["01", "15", "00"],
            '1 hour, 5 minutes'     => ["01", "05", "00"],
            '45 minutes'            => ["00", "45", "00"],
            '1 hour'                => ["01", "00", "00"],
        ];
    }
}
