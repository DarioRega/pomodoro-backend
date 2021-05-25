<?php


namespace Tests\Feature;


use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
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

    public function createSessionWithSteps(): PomodoroSession
    {
        $session = $this->createSession();
        CreateSessionSteps::run($session);
        return $session->fresh();
    }
}
