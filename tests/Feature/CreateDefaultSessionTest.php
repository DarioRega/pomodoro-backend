<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateDefaultSessionTest extends TestCase
{
    use RefreshDatabase;

    const DEFAULT_SESSION_VALUES = [
        'pomodoro_duration' => '00:00:25',
        'small_pause_duration' => '00:00:05',
        'big_pause_duration' => '00:00:15',
        'pomodoro_quantity' => 4,
    ];

    public function test_pomodoro_default_session_creation()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/user/sessions', [
            'session_name' => 'Test Default Session',
        ]);

        $response->assertStatus(201);

        $this->assertEquals(
            'Test Default Session',
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
