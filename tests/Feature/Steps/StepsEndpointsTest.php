<?php

namespace Tests\Feature\Steps;

use App\Events\UpdateSessionEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\SmokeTestCase;
use Tests\Feature\SessionsAndSteps;

class StepsEndpointsTest extends SmokeTestCase
{
    use SessionsAndSteps;
    use RefreshDatabase;

    protected string $baseEndpoint = '/api/user/sessions/current/steps';
    protected array $events = [UpdateSessionEvent::class];

    public function provider(): array
    {
        return [
            'Get current steps' => [
                ['create' => 'createInProgressStep'],
            ],
            'Get current empty steps' => [
                ['create' => 'createPendingStep'],
            ],
            'Get user current step in progress' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current'
                ],
            ],
            'Get user current step paused' => [
                [
                    'create' => 'createPausedStep',
                    'endpoint' => '/current'
                ],
            ],
            'Get user current next pending step' => [
                [
                    'create' => 'createDoneStep',
                    'endpoint' => '/current'
                ],
            ],
            'Get user current no content step' => [
                [
                    'create' => 'createSessionWithSteps',
                    'code' => 204,
                    'endpoint' => '/current',
                    'method' => 'get'
                ],
            ],
            'Start current step' => [
                [
                    'create' => 'createDoneStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'body' => ['type' => 'START'],
                    'events' => [UpdateSessionEvent::class],
                ],
            ],
            'Start current step error' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'code' => 400,
                    'body' => ['type' => 'START'],
                    'errorMessage' => 'Step already started',
                ],
            ],
            'Start current step error no session' => [
                [
                    'create' => 'createSession',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'code' => 400,
                    'body' => ['type' => 'START'],
                    'errorMessage' => 'No current session available',
                ],
            ],
            'Finish current step' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'body' => ['type' => 'FINISH'],
                    'events' => [UpdateSessionEvent::class],
                ],
            ],
            'Finish current step error' => [
                [
                    'create' => 'createPausedStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'code' => 400,
                    'body' => ['type' => 'FINISH'],
                    'errorMessage' => 'Cannot finish a paused step',
                ],
            ],
            'Pause current step (mandatory resting_time)' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'code' => 400,
                    'body' => ['type' => 'PAUSE'],
                    'errorMessage' => 'resting_time is mandatory',
                ],
            ],
            'Pause current step' => [
                [
                    'create' => 'createInProgressStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'body' => ['type' => 'PAUSE', 'resting_time' => '00:01:00'],
                    'events' => [UpdateSessionEvent::class],
                ],
            ],
            'Resume current step' => [
                [
                    'create' => 'createPausedStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'body' => ['type' => 'RESUME'],
                    'events' => [UpdateSessionEvent::class],
                ],
            ],
            'Skip current step' => [
                [
                    'create' => 'createPausedStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'body' => ['type' => 'SKIP'],
                    'events' => [UpdateSessionEvent::class],
                ],
            ],
            'Invalid step type' => [
                [
                    'create' => 'createPausedStep',
                    'endpoint' => '/current/action',
                    'method' => 'post',
                    'code' => 400,
                    'body' => ['type' => 'Kiwi'],
                    'errorMessage' => 'Invalid step action type: Kiwi',
                ],
            ],
        ];
    }
}
