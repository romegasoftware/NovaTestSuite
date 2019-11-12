<?php

namespace Romegadigital\NovaTestSuite\Traits;

use Illuminate\Support\Arr;

trait InteractsWithNovaResources
{
    use MakesNovaHttpRequests;

    /**
     * Model Class of the resource.
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * Resource class.
     *
     * @var string
     */
    protected $resourceClass = '';

    /**
     * Prefilled values for the next request.
     *
     * @var array
     */
    private $prefillValues = [];

    /**
     * Returns default user.
     *
     * @return \Illuminate\Foundation\Auth\User;
     */
    protected function getDefaultUser()
    {
        return factory(config('auth.providers.users.model'))->create();
    }

    /**
     * Get a resource via get request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function getResources($key = '', $user = null)
    {
        return $this->actingAs($user ?? $this->getDefaultUser())
            ->novaGet($this->resourceClass::uriKey(), $key);
    }

    /**
     * Store a resource via post request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function storeResource($data = [], $user = null)
    {
        $resource = $this->mergeData($data, true);

        return $this->actingAs($user ?? $this->getDefaultUser(), 'api')
            ->novaStore($this->resourceClass::uriKey(), $resource->toArray());
    }

    /**
     * Update a resource via post request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function updateResource($data = [], $user = null)
    {
        $resource = $this->mergeData($data);

        return $this->actingAs($user ?? $this->getDefaultUser(), 'api')
            ->novaUpdate($this->resourceClass::uriKey() . '/' . $resource['id'], $resource->toArray());
    }

    /**
     * Delete a resource via delete request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function deleteResource($data = [], $user = null)
    {
        $resource = Arr::only($this->mergeData($data)->toArray(), 'id');

        return $this->actingAs($user ?? $this->getDefaultUser())
            ->novaDelete($this->resourceClass::uriKey(), $resource);
    }

    /**
     * Remap field to what Nova is expecting.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @param array                               $data
     *
     * @return array
     */
    protected function remapResource($resource, $data = [])
    {
        return [];
    }

    /**
     * Assert json actions.
     *
     * @param string $resourceKey
     * @param array  $actions
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function assertHasActions($resourceKey, $actions)
    {
        return $this->novaRequest('get', $resourceKey . '/actions')
            ->assertJson([
                'actions' => $this->mapIndexToName($actions),
            ]);
    }

    /**
     * Assert json filters.
     *
     * @param string $resourceKey
     * @param array  $filters
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function assertHasFilters($resourceKey, $filters)
    {
        return $this->novaRequest('get', $resourceKey . '/filters')
            ->assertJson(
                $this->mapIndexToName($filters)
            );
    }

    /**
     * Assert json lenses.
     *
     * @param string $resourceKey
     * @param array  $lenses
     *
     * @return \Romegadigital\NovaTestSuite\NovaTestResponse
     */
    protected function assertHasLenses($resourceKey, $lenses)
    {
        return $this->novaRequest('get', $resourceKey . '/lenses')
            ->assertJson(
                $this->mapIndexToName($lenses)
            );
    }

    /**
     * Maps array list to name.
     *
     * @param array $list
     *
     * @return array
     */
    private function mapIndexToName($list)
    {
        return collect($list)->mapWithKeys(function ($item, $index) {
            return [$index => ['name' => $item]];
        })->toArray();
    }

    /**
     * Merge resource data.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function mergeData($data = [], $isStoreRequest = false)
    {
        $factory = factory($this->modelClass);
        $resource = $isStoreRequest || isset($data->id) || isset($data['id'])
            ? $factory->make()
            : $factory->create();

        return collect($resource)
            ->merge($this->remapResource($resource, $data))
            ->merge($data)
            ->merge($this->clearPrefilledData());
    }

    /**
     * Set all keys of this resource to 'null'.
     *
     * @param array|string $keys
     *
     * @return self
     */
    protected function setNullValuesOn($keys)
    {
        if (! is_array($keys)) {
            $keys = func_get_args();
        }

        $this->prefillValues = array_combine($keys, array_fill(0, count($keys), null));

        return $this;
    }

    /**
     * Get prefilled resource values and reset array.
     *
     * @return array
     */
    protected function clearPrefilledData()
    {
        return tap($this->prefillValues, function () {
            $this->prefillValues = [];
        });
    }
}
