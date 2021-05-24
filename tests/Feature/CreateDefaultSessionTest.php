<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Models\PomodoroSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateDefaultSessionTest extends TestCase
{
    use RefreshDatabase;

    const DEFAULT_SESSION_VALUES = [
        'goals' => 'Make my tasks like flash',
        'pomodoro_duration' => '00:25:00',
        'small_break_duration' => '00:05:00',
        'big_break_duration' => '00:15:00',
        'pomodoro_quantity' => 4,
    ];

    public function testCreateDefaultSessionFromEndpoint()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'goals' => self::DEFAULT_SESSION_VALUES['goals'],
        ]);

        $response->assertStatus(200);
        $session = $user->fresh()->pomodoroSessions->first();
        $this->assertDefaultSessionValues($session);
        $this->assertCount(8, $session->steps);
    }

    public function testCreateDefaultSessionWithGoals()
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run(
            [ 'goals' => self::DEFAULT_SESSION_VALUES['goals']]
        );

        $session = $user->fresh()->pomodoroSessions->first();
        $this->assertDefaultSessionValues($session);
    }

    public function testCreateDefaultSessionWithoutGoals()
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run();
        $session = $user->fresh()->pomodoroSessions->first();
        $this->assertDefaultSessionValues($session, false);
    }

    private function assertDefaultSessionValues(PomodoroSession $session, $goals = true)
    {
        if ($goals === true) {
            $this->assertEquals(
                self::DEFAULT_SESSION_VALUES['goals'],
                $session->goals
            );
        }

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['pomodoro_duration'],
            $session->pomodoro_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['small_break_duration'],
            $session->small_break_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['big_break_duration'],
            $session->big_break_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['pomodoro_quantity'],
            $session->pomodoro_quantity
        );
    }
}
