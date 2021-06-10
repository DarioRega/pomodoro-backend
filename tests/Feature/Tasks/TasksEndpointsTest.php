<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SmokeTestCase;
use Tests\Feature\SessionsAndSteps;

class TasksEndpointsTest extends SmokeTestCase
{
    use SessionsAndSteps;
    use RefreshDatabase;

    protected string $baseEndpoint = '/api/tasks';

    public function provider(): array
    {
        return [
            'Get current steps' => [
                [
                    'endpoint' => '/status',
                    'jsonCount' => 4,
                ],
            ],
        ];
    }
}
