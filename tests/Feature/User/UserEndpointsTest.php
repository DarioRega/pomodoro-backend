<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Creators\SessionsAndStepsCreator;
use Tests\Feature\Creators\UserCreators;
use Tests\Feature\SmokeTestCase;

class UserEndpointsTest extends SmokeTestCase
{
    use SessionsAndStepsCreator;
    use RefreshDatabase;
    use UserCreators;

    protected string $baseEndpoint = '/api/user';

    public function provider(): array
    {
        return [
            'Update user settings' => [
                [
                    'create' => 'createUser',
                    'endpoint' => '/settings',
                    'method' => 'post',
                    'body' => [
                        'theme' =>  'DARK',
                        'time_display_format' => '12H'
                    ]
                ],
            ],
            'Get user' => [
                [
                    'create' => 'createUser',
                    'endpoint' => '/',
                ],
            ],
        ];
    }
}
