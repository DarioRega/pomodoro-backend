<?php

namespace Tests\Feature\Sessions;

use App\Events\UpdateSessionEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SessionsAndSteps;
use Tests\Feature\SmokeTestCase;

class SessionEndpointsTest extends SmokeTestCase
{
    use SessionsAndSteps;
    use RefreshDatabase;

    protected string $baseEndpoint = '/api/user/sessions';
    protected array $events = [UpdateSessionEvent::class];


    public function provider(): array
    {
        return [
            'Get session' => [
                ['create' => 'createSessionWithSteps'],
            ],
            'Get current session' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current'
                ],
            ],
            'Get current empty session' => [
                [
                    'create' => 'createSession',
                    'endpoint' => '/current',
                    'code' => 204
                ],
            ],
            'Abort session' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current/abort',
                    'events' => [UpdateSessionEvent::class],
                    'code' => 200
                ],
            ],
            'Abort session error no current session' => [
                [
                    'create' => 'createPendingStep',
                    'endpoint' => '/current/abort',
                    'code' => 404,
                    'errorMessage' => 'You have no current session'
                ],
            ],
        ];
    }
}
