<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Creators\UserCreators;
use Tests\Feature\SmokeTestCase;
use Tests\Feature\SessionsAndSteps;

class UserEndpointsTest extends SmokeTestCase
{
    use SessionsAndSteps;
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
                        'display_format' => '12H'
                    ]
                ],
            ],
        ];
    }
}
