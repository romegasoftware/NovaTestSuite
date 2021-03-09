<?php

namespace RomegaSoftware\NovaTestSuite\Traits;

use Illuminate\Testing\TestResponse;

trait MakesNovaHttpRequests
{
    /**
     * Makes a nova get request.
     *
     * @param string     $resourceKey
     * @param string|int $key
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaGet($resourceKey, $key = null): TestResponse
    {
        return $this->novaRequest('get', $resourceKey . ($key ? "/$key" : ''));
    }

    /**
     * Makes a nova store request.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaStore($resourceKey, $data): TestResponse
    {
        return $this->novaRequest('post', $resourceKey, $data);
    }

    /**
     * Makes a nova store request.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaUpdate($resourceKey, $data): TestResponse
    {
        return $this->novaRequest('put', $resourceKey, $data);
    }

    /**
     * Makes a nova delete request.
     *
     * @param string $resourceKey
     * @param array  $ids
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaDelete($resourceKey, $ids): TestResponse
    {
        return $this->novaRequest('delete', $resourceKey, [
            'resources' => $ids,
        ]);
    }

    /**
     * Makes a nova http request.
     *
     * @param string $method
     * @param string $resourceKey
     * @param array  $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaRequest($method, $resourceKey, $data = []): TestResponse
    {
        return $this->{$method}("/nova-api/$resourceKey", $data);
    }
}
