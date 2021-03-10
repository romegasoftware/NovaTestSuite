<?php

namespace RomegaSoftware\NovaTestSuite\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Testing\TestResponse;

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
    protected function getDefaultUser(): User
    {
        return config('auth.providers.users.model')::factory()->create();
    }

    /**
     * Act as default user if not already authenticated.
     *
     * @return self
     */
    protected function beDefaultUser(): self
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
     * @return \Illuminate\Testing\TestResponse
     */
    protected function getResources($key = ''): TestResponse
    {
        return $this->beDefaultUser()
            ->novaGet($this->resourceClass::uriKey(), $key);
    }

    /**
     * Store a resource via post request.
     *
     * @param array                               $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function storeResource($data = []): TestResponse
    {
        $resource = $this->mergeData($data, true);

        return $this->beDefaultUser()
            ->novaStore($this->resourceClass::uriKey(), $resource);
    }

    /**
     * Update a resource via post request.
     *
     * @param array                               $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function updateResource($data = []): TestResponse
    {
        $resource = $this->mergeData($data);

        return $this->beDefaultUser()
            ->novaUpdate($this->resourceClass::uriKey() . '/' . $resource['id'], $resource);
    }

    /**
     * Delete a resource via delete request.
     *
     * @param array|string                        $data
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function deleteResource($data = []): TestResponse
    {
        if (!is_array($data)) {
            $data = ['id' => $data];
        }

        $resource = Arr::only($this->mergeData($data), 'id');

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
    protected function remapResource($resource): array
    {
        return [];
    }

    /**
     * Assert json actions.
     *
     * @param array $actions
     *
     * @return \Illuminate\Testing\TestResponse
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
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertHasFilters($filters): TestResponse
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
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertHasLenses($lenses): TestResponse
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
    private function mapIndexToName($list): array
    {
        return collect($list)->mapWithKeys(function ($item, $index) {
            return [$index => ['name' => $item]];
        })->toArray();
    }

    /**
     * Merge resource data.
     *
     * @param array|Arrayable $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function mergeData($data = [], $isStoreRequest = false): array
    {
        if (!is_array($data) && $data instanceof Model) {
            $data = $data->toArray();
        }

        $factory = isset($this->factoryClass) ? $this->factoryClass::new() : $this->modelClass::factory();
        $resource = $isStoreRequest || isset($data['id'])
            ? $factory->make($data)
            : $factory->create($data);

        $resource->makeVisible($resource->getHidden())
            ->forceFill($this->clearPrefilledData());

        return array_merge($resource->toArray(), $this->remapResource($resource));
    }

    /**
     * Set all keys of this resource to 'null'.
     *
     * @param array|string $keys
     *
     * @return self
     */
    protected function setNullValuesOn($keys): self
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
    private function clearPrefilledData(): array
    {
        return tap($this->prefillValues, function () {
            $this->prefillValues = [];
        });
    }
}
