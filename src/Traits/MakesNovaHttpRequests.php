<?php

namespace Romegadigital\NovaTestSuite\Traits;

use Romegadigital\NovaTestSuite\NovaTestResponse;

trait MakesNovaHttpRequests
{
    /**
     * Makes a nova get request.
     *
     * @param string     $resourceKey
     * @param string|int $key
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
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
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
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
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
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
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
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
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function novaRequest($method, $resourceKey, $data = [])
    {
        return new NovaTestResponse(
            $this->{$method}("/nova-api/$resourceKey", $data),
            $data
        );
    }
}
