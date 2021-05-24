<?php


namespace Tests\Feature;


use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Models\PomodoroSession;
use App\Models\User;

trait Sessions
{
    public function createSession(array $data = []): PomodoroSession
    {
        $this->actingAs($user =User::factory()->create());
        CreateDefaultSession::run($data);
        return $user->fresh()->pomodoroSessions->first();
    }
}
