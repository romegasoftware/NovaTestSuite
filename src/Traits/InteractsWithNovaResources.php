<?php

namespace Romegadigital\NovaTestSuite\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

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
     * Act as default user if not already authenticated.
     *
     * @return self
     */
    protected function beDefaultUser()
    {
        if ($this->isAuthenticated('api')) {
            return $this;
        }

        return $this->actingAs($this->getDefaultUser());
    }

    /**
     * Get a resource via get request.
     *
     * @param array                               $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function getResources($key = '')
    {
        return $this->beDefaultUser()
            ->novaGet($this->resourceClass::uriKey(), $key);
    }

    /**
     * Store a resource via post request.
     *
     * @param array                               $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function storeResource($data = [])
    {
        $resource = $this->mergeData($data, true);

        return $this->beDefaultUser()
            ->novaStore($this->resourceClass::uriKey(), $resource->toArray());
    }

    /**
     * Update a resource via post request.
     *
     * @param array                               $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function updateResource($data = [])
    {
        $resource = $this->mergeData($data);

        return $this->beDefaultUser()
            ->novaUpdate(
                $this->resourceClass::uriKey() . '/' . $resource['id'],
                $resource->toArray()
            );
    }

    /**
     * Delete a resource via delete request.
     *
     * @param array|string                        $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function deleteResource($data = [])
    {
        if (!is_array($data)) {
            $data = ['id' => $data];
        }

        $resource = Arr::only($this->mergeData($data)->toArray(), 'id');

        return $this->beDefaultUser()
            ->novaDelete($this->resourceClass::uriKey(), $resource);
    }

    /**
     * Remap field to what Nova is expecting.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return array
     */
    protected function remapResource($resource)
    {
        return [];
    }

    /**
     * Assert json actions.
     *
     * @param array $actions
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasActions($actions)
    {
        return $this->beDefaultUser()
            ->novaRequest('get', $this->resourceClass::uriKey() . '/actions')
            ->assertJson([
                'actions' => $this->mapIndexToName($actions),
            ]);
    }

    /**
     * Assert json filters.
     *
     * @param array $filters
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasFilters($filters)
    {
        return $this->beDefaultUser()
            ->novaRequest('get', $this->resourceClass::uriKey() . '/filters')
            ->assertJson(
                $this->mapIndexToName($filters)
            );
    }

    /**
     * Assert json lenses.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasLenses($lenses)
    {
        return $this->beDefaultUser()
            ->novaRequest('get', $this->resourceClass::uriKey() . '/lenses')
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

        if (!is_array($data) && $data instanceof Model) {
            $data = $data->toArray();
        }

        $preMerged = $resource->forceFill($data);
        $preMerged->makeVisible($preMerged->getHidden());

        return $preMerged
            ->forceFill($this->remapResource($preMerged))
            ->forceFill($this->clearPrefilledData());
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
        if (!is_array($keys)) {
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
    private function clearPrefilledData()
    {
        return tap($this->prefillValues, function () {
            $this->prefillValues = [];
        });
    }
}
