<?php


namespace Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Notification;
use Tests\TestCase;

class SmokeTestCase extends TestCase
{
    protected string $baseEndpoint = '';
    protected array $events = [];
    private array $baseParameters = [
        'endpoint' => '/',
        'code' => 200,
        'method' => 'get',
        'errorMessage' => '',
        'create' => null,
        'body' => [],
        'events' => [],
        'jsonCount' => null,
    ];

    /**
     * @dataProvider provider
     */
    public function testSmokeEndpoints(array $parameters)
    {
        $parameters = array_merge($this->baseParameters, $parameters);

        Event::fake($this->events);
        $this->assertEvents($parameters);

        $this->callClassFunctionByFunctionName($parameters);

        $endpoint = $this->baseEndpoint . $parameters['endpoint'];

        $response = $this->callEndpoint($parameters['method'], $endpoint, $parameters['body']);

        $response->assertStatus($parameters['code']);

        $this->countJsonResponseObjects($response, $parameters);

        $this->assertErrorMessage($response, $parameters['errorMessage']);
    }

    private function assertErrorMessage($response, $errorMessage)
    {
        if ($errorMessage !== '') {
            $response->assertJson(['message' => __($errorMessage)]);
        }
    }

    private function assertEvents(array $parameters)
    {
        if (!empty($parameters['events'])) {
            $this->expectsEvents($this->events);
            foreach ($parameters['events'] as $event) {
                Event::assertDispatched($event);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function callEndpoint(string $method, string $endpoint, array $body): TestResponse
    {
        if ($method === 'get') {
            return $this->get($endpoint);
        }

        if ($method === 'post') {
            return $this->post($endpoint, $body);
        }

        throw new Exception('Method: '. $method . ' is not supported yet');
    }

    private function callClassFunctionByFunctionName($parameters)
    {
        if (isset($parameters['create'])) {
            call_user_func(array($this, $parameters['create']));
        }
    }

    private function countJsonResponseObjects($response, $parameters)
    {
        if ($parameters['jsonCount'] !== null) {
            $response->assertJsonCount($parameters['jsonCount']);
        }
    }

    public function provider(): array
    {
        return [];
    }
}
