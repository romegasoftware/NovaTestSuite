<?php

namespace Romegadigital\NovaTestSuite;

use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Foundation\Testing\TestResponse;

class NovaTestResponse
{
    use ForwardsCalls;

    /**
     * @var TestRepsonse
     */
    protected $testResponse;

    /**
     * @var array|null
     */
    protected $sentData;

    public function __construct(TestResponse $testResponse, $sentData)
    {
        $this->testResponse = $testResponse;
        $this->sentData = $sentData;
    }

    /**
     * Assert whether the nova request has failed.
     *
     * @return $this
     */
    public function assertFailed()
    {
        $this->testResponse->assertRedirect();

        return $this;
    }

    /**
     * Assert whether the response includes required session errors.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function assertRequiredFields($fields)
    {
        $sessionErrors = collect($fields)->mapWithKeys(function ($field) {
            return [$field => __('validation.required', ['attribute' => str_replace('_', ' ', $field)])];
        })->all();

        $this->assertSessionHasErrors($sessionErrors);

        return $this;
    }

    /**
     * Dumps errors to easier debug responses from nova.
     */
    public function dumpErrors()
    {
        if (app('session.store')->has('errors')) {
            dump(app('session.store')->get('errors')->getBag('default'));
        }

        dump($this->sentData);
        dump($this->testResponse->json());

        return $this;
    }

    /**
     * Handle dynamic calls into test response.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->forwardCallTo($this->testResponse, $method, $args);
    }
}
