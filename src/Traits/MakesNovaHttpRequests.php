<?php

namespace RomegaSoftware\NovaTestSuite\Traits;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

trait MakesNovaHttpRequests
{
    /**
     * Makes a nova get request.
     *
     * @param string $resourceKey
     * @param null $key
     * @param string $query
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function novaGet($resourceKey, $key = null, $query = ''): TestResponse
    {
        return $this->novaRequest('get', $resourceKey . ($key ? "/$key" : ''), [], $query);
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
        $query = Arr::query([
            'editing' => true,
            'editMode' => 'create'
        ]);

        return $this->novaRequest('post', $resourceKey, $data, $query);
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
        $query = Arr::query([
            'editing' => true,
            'editMode' => 'update'
        ]);

        return $this->novaRequest('put', $resourceKey, $data, $query);
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
    protected function novaRequest($method, $resourceKey, $data = [], $query = ''): TestResponse
    {
        return $this->{$method}("/nova-api/$resourceKey?$query", $data);
    }
}
