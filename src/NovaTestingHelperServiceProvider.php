<?php

namespace Romegadigital\NovaTestSuite;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
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
