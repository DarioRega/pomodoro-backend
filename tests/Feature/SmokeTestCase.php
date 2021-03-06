<?php


namespace Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SmokeTestCase extends TestCase
{
    protected string $baseEndpoint = '';
    private array $baseParameters = [
        'endpoint' => '/',
        'code' => 200,
        'method' => 'get',
        'errorMessage' => '',
        'assertJson' => [],
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

        $this->assertEvents($parameters);

        $model = $this->callClassFunctionByFunctionName($parameters);

        $endpoint = $this->baseEndpoint . $this->replaceModelCreatedIdIntoEndpoint($parameters, $model);

        $response = $this->callEndpoint($parameters['method'], $endpoint, $parameters['body']);

        $this->assertJsonResponse($response, $parameters);

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

    private function replaceModelCreatedIdIntoEndpoint($parameters, $model): string
    {
        if (isset($model['id'])) {
            return str_replace('{id}', $model['id'], $parameters['endpoint']);
        }
        return $parameters['endpoint'];
    }

    private function assertEvents(array $parameters)
    {
        if (!empty($parameters['events'])) {
            Event::fake($parameters['events']);
            $this->expectsEvents($parameters['events']);
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

        if ($method === 'delete') {
            return $this->delete($endpoint);
        }

        if ($method === 'post') {
            return $this->post($endpoint, $body);
        }

        throw new Exception('Method: '. $method . ' is not supported yet');
    }

    private function callClassFunctionByFunctionName($parameters)
    {
        if (isset($parameters['create'])) {
            return call_user_func(array($this, $parameters['create']));
        }
    }
    private function assertJsonResponse($response, $parameters)
    {
        if (!empty($parameters['assertJson'])) {
            $response->assertJson($parameters['assertJson']);
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
