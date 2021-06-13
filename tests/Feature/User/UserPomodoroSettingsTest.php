<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\Feature\Creators\UserCreators;
use Tests\Feature\SmokeTestCase;

class UserPomodoroSettingsTest extends SmokeTestCase
{
    use SessionsAndStepsCreator;
    use RefreshDatabase;
    use UserCreators;

    protected string $baseEndpoint = '/api/user';

    public function provider(): array
    {
        return [
            'Create pomodoro settings' => [
                [
                    'create' => 'createUser',
                    'endpoint' => '/pomodoro-settings',
                    'method' => 'post',
                    'code' => 201,
                    'body' => [
                        'pomodoro_duration' => '20',
                        'small_break_duration' => '3',
                        'big_break_duration' => '20',
                        'pomodoro_quantity' => '5',
                    ],
                    'assertJson' => [
                        'pomodoro_duration' => '00:20:00',
                        'small_break_duration' => '00:03:00',
                        'big_break_duration' => '00:20:00',
                        'pomodoro_quantity' => '5',
                    ]
                ],
            ],
            'Update pomodoro settings' => [
                [
                    'create' => 'createUserPomodoroSettings',
                    'endpoint' => '/pomodoro-settings/{id}/update',
                    'method' => 'post',
                    'code' => 200,
                    'body' => [
                        'pomodoro_duration' => '19',
                        'small_break_duration' => '2',
                        'big_break_duration' => '18',
                        'pomodoro_quantity' => '4',
                    ],
                    'assertJson' => [
                        'pomodoro_duration' => '00:19:00',
                        'small_break_duration' => '00:02:00',
                        'big_break_duration' => '00:18:00',
                        'pomodoro_quantity' => '4',
                    ]
                ],
            ],
            'Delete pomodoro settings' => [
                [
                    'create' => 'createUserPomodoroSettings',
                    'endpoint' => '/pomodoro-settings/{id}',
                    'method' => 'delete',
                    'code' => 200,
                ],
            ],
            'Update other user pomodoro settings error' => [
                [
                    'create' => 'createOtherUserPomodoroSettings',
                    'endpoint' => '/pomodoro-settings/{id}/update',
                    'method' => 'post',
                    'code' => 403,
                    'errorMessage' => 'You are not allowed to update this settings',
                    'body' => [
                        'small_break_duration' => '2',
                    ],
                ],
            ],
            'Delete other user pomodoro settings error' => [
                [
                    'create' => 'createOtherUserPomodoroSettings',
                    'endpoint' => '/pomodoro-settings/{id}',
                    'method' => 'delete',
                    'code' => 403,
                    'errorMessage' => 'You are not allowed to delete this settings',
                ],
            ],
        ];
    }
}
