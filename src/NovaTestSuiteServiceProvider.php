<?php

namespace Romegadigital\NovaTestSuite;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Str;
use Romegadigital\NovaTestSuite\Commands\CreateResourceTestCase;
use Romegadigital\NovaTestSuite\Commands\PublishNovaResourceTestCase;

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
        TestResponse::macro('dumpErrors', function () {
            if (app('session.store')->has('errors')) {
                dump(app('session.store')->get('errors')->getBag('default'));
            }

            dump($this->json());

            return $this;
        });

        TestResponse::macro('assertNovaFailed', function () {
            return $this->assertRedirect();
        });

        TestResponse::macro('assertRequiredFields', function ($fields) {
            $sessionErrors = collect($fields)->mapWithKeys(function ($field) {
                return [$field => __('validation.required', ['attribute' => Str::title(str_replace('_', ' ', $field))])];
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
