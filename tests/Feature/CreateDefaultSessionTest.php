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
        'session_name' => 'Test Default Session',
        'pomodoro_duration' => '00:00:25',
        'small_pause_duration' => '00:00:05',
        'big_pause_duration' => '00:00:15',
        'pomodoro_quantity' => 4,
    ];

    public function test_pomodoro_default_session_creation_endpoint()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'session_name' => self::DEFAULT_SESSION_VALUES['session_name'],
        ]);

        $response->assertStatus(201);

        $this->assertDefaultSessionValues($user);
    }

    public function test_pomodoro_default_session_creation_validation_name()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'session_name' => '',
        ]);

        $response->assertStatus(422)->assertJson([
            'errors' => [
                'session_name' => [
                    'The session name field is required.'
                ]
            ],
        ]);

        $response = $this->postJson('/api/user/sessions', [
            'session_name' => 'aaa',
        ]);

        $response->assertStatus(422)->assertJson([
            'errors' => [
                'session_name' => [
                    'The session name must be at least 4 characters.'
                ]
            ],
        ]);
    }

    public function test_pomodoro_default_session_creation_action()
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run(self::DEFAULT_SESSION_VALUES['session_name']);
        $this->assertDefaultSessionValues($user);
    }

    private function assertDefaultSessionValues(User $user) {
        $this->assertEquals(
            self::DEFAULT_SESSION_VALUES['session_name'],
            $user->fresh()->pomodoroSessions->first()->name
        );

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
