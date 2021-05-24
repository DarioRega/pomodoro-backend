<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Steps\Create\CreateStep;
use App\Enums\StepType;
use App\Models\PomodoroSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateStepTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePomodoroStep()
    {
        $user = User::factory()->create();
        $session = PomodoroSession::factory()->for($user)->create();

        $step = CreateStep::run(StepType::Pomodoro(), $session);

        $this->assertEquals(
            $session->id,
            $step->fresh()->pomodoro_session_id
        );

        $this->assertEquals(
            $session->pomodoro_duration,
            $step->fresh()->duration
        );

        $this->assertEquals(
            $session->pomodoro_duration,
            $step->fresh()->resting_time
        );

        $this->assertEquals(
            StepType::Pomodoro(),
            $step->fresh()->type
        );

        $this->assertNull($step->fresh()->started_at);
        $this->assertNull($step->fresh()->skipped_at);
        $this->assertNull($step->fresh()->finished_at);
    }
}
