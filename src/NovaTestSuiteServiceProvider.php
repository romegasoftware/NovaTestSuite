<?php

namespace RomegaSoftware\NovaTestSuite;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Str;
use RomegaSoftware\NovaTestSuite\Commands\CreateResourceTestCase;
use RomegaSoftware\NovaTestSuite\Commands\PublishNovaResourceTestCase;

class NovaTestSuiteServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishNovaResourceTestCase::class,
                CreateResourceTestCase::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        TestResponse::macro('assertNovaFailed', function () {
            return $this->assertRedirect();
        });

        TestResponse::macro('assertRequiredFields', function ($fields) {
            $sessionErrors = collect($fields)->mapWithKeys(function ($field, $key) {
                $key = is_string($key) ? $key : $field;
                $attribute = is_callable($field)
                    ? $field($key)
                    : Str::of($field)->studly()->snake(' ')->title();

                return [$key => __('validation.required', ['attribute' => $attribute])];
            })->all();

            return $this->assertSessionHasErrors($sessionErrors);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PublishNovaResourceTestCase::class,
            CreateResourceTestCase::class,
        ];
    }
}
