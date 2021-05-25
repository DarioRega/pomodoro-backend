<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Enums\StepStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepsUserActionsTest extends TestCase
{
    use RefreshDatabase;
    use Sessions;

    public function testStartStep()
    {
        $this->markTestSkipped('Test skipping');
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        $step = $session->fresh()->steps()->first();
        $step = StartStep::run($step);

        $this->assertNotNull($step->started_at);
        $this->assertEquals(StepStatus::IN_PROGRESS, $step->status);
    }
}
