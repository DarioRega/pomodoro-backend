<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SmokeTestCase;
use Tests\Feature\UserHelpers;

class TaskEndpointsTest extends SmokeTestCase
{
    use UserHelpers;
    use RefreshDatabase;

    public function provider(): array
    {
        return [
            'Get current steps' => [
                [
                    'endpoint' => '/api/tasks/status',
                    'jsonCount' => 4,
                ],
            ],
            'Create task with name only' => [
                [
                    'create' => 'createUser',
                    'endpoint' => '/api/user/tasks',
                    'method' => 'post',
                    'body' => ['name' => 'test name'],
                    'assertJson' => [
                        'name' => 'test name',
                        'task_status' => [
                            'name' => 'TODO'
                        ]
                    ],
                    'code' => 201
                ],
            ],
            'Create task with all attributes' => [
                [
                    'create' => 'createUser',
                    'endpoint' => '/api/user/tasks',
                    'method' => 'post',
                    'body' => [
                        'name' => 'test name',
                        'description' => 'test description',
                        'deadline' => '2024-10-10',
                    ],
                    'assertJson' => [
                        'name' => 'test name',
                        'description' => 'test description',
                        'deadline' => '2024-10-10',
                        'task_status' => [
                            'name' => 'TODO'
                        ]
                    ],
                    'code' => 201
                ],
            ],
        ];
    }
}
