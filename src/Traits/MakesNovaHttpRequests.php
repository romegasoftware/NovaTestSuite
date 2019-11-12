<?php

namespace Romegadigital\NovaTestingHelper\Traits;

use Romegadigital\NovaTestingHelper\NovaTestResponse;

trait MakesNovaHttpRequests
{
    /**
     * Makes a nova get request.
     *
     * @param string     $resourceKey
     * @param string|int $key
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function novaGet($resourceKey, $key = null)
    {
        return $this->novaRequest('get', $resourceKey . ($key ? "/$key" : ''));
    }

    /**
     * Makes a nova store request.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function novaStore($resourceKey, $data)
    {
        return $this->novaRequest('post', $resourceKey, $data);
    }

    /**
     * Makes a nova store request.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function novaUpdate($resourceKey, $data)
    {
        return $this->novaRequest('put', $resourceKey, $data);
    }

    /**
     * Makes a nova delete request.
     *
     * @param string $resourceKey
     * @param array  $ids
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function novaDelete($resourceKey, $ids)
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
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function novaRequest($method, $resourceKey, $data = [])
    {
        return new NovaTestResponse(
            $this->{$method}("/nova-api/$resourceKey", $data),
            $data
        );
    }
}
