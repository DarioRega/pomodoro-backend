<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateDefaultSessionTest extends TestCase
{
    use RefreshDatabase;

    const DEFAULT_SESSION_VALUES = [
        'goals' => 'Make my tasks like flash',
        'pomodoro_duration' => '00:00:25',
        'small_pause_duration' => '00:00:05',
        'big_pause_duration' => '00:00:15',
        'pomodoro_quantity' => 4,
    ];

    public function testCreateDefaultSessionFromEndpoint()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'goals' => self::DEFAULT_SESSION_VALUES['goals'],
        ]);

        $response->assertStatus(201);

        $this->assertDefaultSessionValues($user);
    }

    public function testCreateDefaultSessionWithGoals()
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run(self::DEFAULT_SESSION_VALUES['goals']);
        $this->assertDefaultSessionValues($user);
    }

    public function testCreateDefaultSessionWithoutGoals()
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run();
        $this->assertDefaultSessionValues($user, false);
    }

    private function assertDefaultSessionValues(User $user, $goals = true)
    {
        if ($goals === true) {
            $this->assertEquals(
                self::DEFAULT_SESSION_VALUES['goals'],
                $user->fresh()->pomodoroSessions->first()->goals
            );
        }

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['pomodoro_duration'],
            $user->fresh()->pomodoroSessions->first()->pomodoro_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['small_pause_duration'],
            $user->fresh()->pomodoroSessions->first()->small_pause_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['big_pause_duration'],
            $user->fresh()->pomodoroSessions->first()->big_pause_duration
        );

        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['pomodoro_quantity'],
            $user->fresh()->pomodoroSessions->first()->pomodoro_quantity
        );
    }
}
