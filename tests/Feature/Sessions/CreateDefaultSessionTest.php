<?php

namespace Tests\Feature\Sessions;

use App\Models\PomodoroSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Data;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\TestCase;

class CreateDefaultSessionTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndStepsCreator;

    public function testCreateDefaultSessionFromEndpoint()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'goals' => Data::DEFAULT_SESSION_VALUES['goals'],
        ]);

        $response->assertStatus(200);
        $session = $user->fresh()->pomodoroSessions->first();
        $this->assertDefaultSessionValues($session);
        $this->assertCount(8, $session->steps);
    }

    public function testCreateDefaultSessionWithGoals()
    {
        $session = $this->createSession(
            ['goals' => Data::DEFAULT_SESSION_VALUES['goals']]
        );

        $this->assertDefaultSessionValues($session);
    }

    public function testCreateDefaultSessionWithoutGoals()
    {
        $session = $this->createSession(
            ['goals' => Data::DEFAULT_SESSION_VALUES['goals']]
        );
        $this->assertDefaultSessionValues($session, false);
    }

    private function assertDefaultSessionValues(PomodoroSession $session, $goals = true)
    {
        if ($goals === true) {
            $this->assertEquals(
                Data::DEFAULT_SESSION_VALUES['goals'],
                $session->goals
            );
        }

        $this->assertEquals(
            Data::DEFAULT_SESSION_VALUES['pomodoro_duration'],
            $session->pomodoro_duration
        );

        $this->assertEquals(
            Data::DEFAULT_SESSION_VALUES['small_break_duration'],
            $session->small_break_duration
        );

        $this->assertEquals(
            Data::DEFAULT_SESSION_VALUES['big_break_duration'],
            $session->big_break_duration
        );

        $this->assertEquals(
            Data::DEFAULT_SESSION_VALUES['pomodoro_quantity'],
            $session->pomodoro_quantity
        );
    }
}
