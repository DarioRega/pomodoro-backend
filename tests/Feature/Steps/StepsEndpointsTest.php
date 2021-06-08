<?php

namespace Tests\Feature\Steps;

use App\Events\UserAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Feature\SessionsAndSteps;
use Tests\TestCase;

class StepsEndpointsTest extends TestCase
{
    use SessionsAndSteps;
    use RefreshDatabase;

    private string $baseEndpoint = '/api/user/sessions/current/steps';

    /**
     * @dataProvider provider
     */
    public function testStepEndpoint(
        string $create,
        string $endpoint,
        string $method = 'get',
        array $data = [],
        int $code = 200,
        string $errorMessage = ''
    ) {
        Event::fake([
            UserAction::class,
        ]);

        $this->callClassFunctionByFunctionName($create);

        $endpoint = $this->baseEndpoint . $endpoint;

        $response = $method === 'get' ? $this->get($endpoint) : $this->post($endpoint, $data);
        $response->assertStatus($code);
        if ($errorMessage !== '') {
            $response->assertJson(['message' => __($errorMessage)]);
        }
    }

    public function provider(): array
    {
        return [
            'Get current Steps' => [
                'createInProgressStep',
                '/'
            ],
            'Get User Current Step In Progress' => [
                'createInProgressStep',
                '/current'
            ],
            'Get User Current Step Paused' => [
                'createPausedStep',
                '/current'
            ],
            'Get User Current next Pending step' => [
                'createDoneStep',
                '/current'
            ],
            'Get User Current null Step' => [
                'createSessionWithSteps',
                '/current'
            ],
            'Start Current Step' => [
                'createDoneStep',
                '/current/action',
                'post',
                ['type' => 'START'],
            ],
            'Start Current Step Error' => [
                'createInProgressStep',
                '/current/action',
                'post',
                ['type' => 'START'],
                400,
                'Step already started'
            ],
            'Finish Current Step' => [
                'createInProgressStep',
                '/current/action',
                'post',
                ['type' => 'FINISH'],
            ],
            'Finish Current Step Error' => [
                'createPausedStep',
                '/current/action',
                'post',
                ['type' => 'FINISH'],
                400,
                'Cannot finish a paused step'
            ],
            'Pause Current Step (mandatory resting_time)' => [
                'createInProgressStep',
                '/current/action',
                'post',
                ['type' => 'PAUSE'],
                400,
                'resting_time is mandatory',
            ],
            'Pause Current Step' => [
                'createInProgressStep',
                '/current/action',
                'post',
                ['type' => 'PAUSE', 'resting_time' => '00:01:00']
            ],
            'Resume Current Step' => [
                'createPausedStep',
                '/current/action',
                'post',
                ['type' => 'RESUME'],
                200
            ],
            'Skip Current Step' => [
                'createPausedStep',
                '/current/action',
                'post',
                ['type' => 'SKIP'],
                200
            ],
            'Invalid step type' => [
                'createPausedStep',
                '/current/action',
                'post',
                ['type' => 'Kiwi'],
                400,
                'Invalid step action type: Kiwi'
            ],
        ];
    }

    private function callClassFunctionByFunctionName($create)
    {
        call_user_func(array($this, $create));
    }
}
