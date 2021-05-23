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

        $step = CreateStep::run(StepType::POMODORO(), $session);
        $step = $step->fresh();

        $this->assertEquals(
            $session->id,
            $step->pomodoro_session_id
        );

        $this->assertEquals(
            $session->pomodoro_duration,
            $step->duration
        );

        $this->assertEquals(
            $session->pomodoro_duration,
            $step->resting_time
        );

        $this->assertEquals(
            StepType::POMODORO(),
            $step->type
        );

        $this->assertNull($step->started_at);
        $this->assertNull($step->skipped_at);
        $this->assertNull($step->finished_at);
    }
}
