<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Creators\TasksCreators;
use Tests\Feature\Creators\UserCreators;
use Tests\Feature\SmokeTestCase;

class TaskEndpointsTest extends SmokeTestCase
{
    use UserCreators;
    use RefreshDatabase;
    use TasksCreators;

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
            'Delete task' => [
                [
                    'create' => 'createTask',
                    'endpoint' => '/api/user/tasks/{id}',
                    'method' => 'delete',
                    'code' => 200
                ],
            ],
            'Cannot delete another user task' => [
                [
                    'create' => 'createOtherUserTask',
                    'endpoint' => '/api/user/tasks/{id}',
                    'method' => 'delete',
                    'code' => 403,
                    'errorMessage' => 'You are not allowed to update this task',
                ],
            ],
            'Update task' => [
                [
                    'create' => 'createTask',
                    'endpoint' => '/api/user/tasks/{id}/update',
                    'method' => 'post',
                    'code' => 200,
                    'body' => [
                        'name' => 'Picaro Picaro',
                        'description' => 'puma puma',
                        'deadline' => '2024-10-10',
                    ],
                    'assertJson' => [
                        'name' => 'Picaro Picaro',
                        'description' => 'puma puma',
                        'deadline' => '2024-10-10',
                    ],
                ],
            ],
            'Cannot update another user task' => [
                [
                    'create' => 'createOtherUserTask',
                    'endpoint' => '/api/user/tasks/{id}/update',
                    'method' => 'post',
                    'code' => 403,
                    'body' => [
                        'name' => 'Picaro Picaro',
                    ],
                    'errorMessage' => 'You are not allowed to update this task',
                ],
            ],
        ];
    }
}
