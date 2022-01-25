<?php

namespace RomegaSoftware\NovaTestSuite\Traits;

use Illuminate\Testing\TestResponse;

trait AssertsNovaRelationships
{
    /**
     * Assert relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertRelationships($relationships): TestResponse
    {
        $resource = $this->mergeData([]);
        return $this->getResources($resource['id'])
            ->assertJsonFragment(
                $this->mapIndexToRelationship($relationships)
            );
    }

    /**
     * Assert hasOne relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertHasOneRelationships($relationships): TestResponse
    {
        return $this->assertRelationships(['hasOneRelationship' => $relationships]);
    }

    /**
     * Assert hasMany relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertHasManyRelationships($relationships): TestResponse
    {
        return $this->assertRelationships(['hasManyRelationship' => $relationships]);
    }

    /**
     * Assert BelongsTo relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertBelongsToRelationships($relationships): TestResponse
    {
        return $this->assertRelationships(['belongsToRelationship' => $relationships]);
    }

    /**
     * Assert BelongsToMany relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertBelongsToManyRelationships($relationships): TestResponse
    {
        return $this->assertRelationships(['belongsToManyRelationship' => $relationships]);
    }

    /**
     * Assert MorphTo relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertMorphToRelationships($relationships): TestResponse
    {
        return $this->assertRelationships(['morphToRelationship' => $relationships]);
    }

    /**
     * Assert MorphOne relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertMorphOneRelationships($relationships): TestResponse
    {
        return $this->assertHasOneRelationships($relationships);
    }

    /**
     * Assert MorphMany relationship.
     *
     * @param array $lenses
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function assertMorphManyRelationships($relationships): TestResponse
    {
        return $this->assertHasManyRelationships($relationships);
    }

    /**
     * Maps array list to a relationship.
     *
     * @param array $list
     *
     * @return array
     */
    private function mapIndexToRelationship($list): array
    {
        return collect($list)->mapWithKeys(function ($models, $relationship) {
            return collect($models)->mapWithKeys(
                    fn($model) => [$relationship => $model]
                )->toArray();
        })->toArray();
    }
}
