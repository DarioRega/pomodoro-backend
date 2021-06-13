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
        ];
    }
}
