<?php

namespace Tests\Feature\Sessions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class SessionEndpointsTest extends TestCase
{
    use SessionsAndSteps;
    use RefreshDatabase;

    public function testGetUserSessions()
    {
        $this->createSessionWithSteps();
        $response = $this->get('/api/user/sessions');
        $response->assertStatus(200);
    }

    public function testGetUserCurrentSession()
    {
        $this->createInProgressStep();
        $response = $this->get('/api/user/sessions/current');
        $response->assertStatus(200);
    }
}
