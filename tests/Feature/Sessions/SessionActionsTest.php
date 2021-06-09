<?php

namespace Tests\Feature\Sessions;

use App\Actions\Pomodoro\Sessions\CreateDefaultSession;
use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class SessionActionsTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    public function testStartSession()
    {
        $session = $this->createSessionWithSteps();

        $response = $this->getJson('/api/user/sessions/' . $session->id . '/start');
        $response->assertStatus(200);
    }

    public function testStartSessionNotAllowed()
    {
        $session = $this->createSessionWithSteps();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/user/sessions/' . $session->id . '/start');
        $response->assertJson(['message' => __('You are not allowed to start this session')]);
        $response->assertStatus(403);
    }

    public function testCannotHave2sessionsRunning()
    {
        $this->createInProgressStep();
        $session2 = CreateDefaultSession::run();
        CreateSessionSteps::run($session2);

        $response = $this->getJson('/api/user/sessions/' . $session2->id . '/start');
        $response->assertJson(['message' => __('You cannot have 2 session running')]);
        $response->assertStatus(400);
    }
}
