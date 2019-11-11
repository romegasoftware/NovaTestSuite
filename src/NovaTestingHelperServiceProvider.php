<?php

namespace Romegadigital\NovaTestingHelper;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Romegadigital\NovaTestingHelper\Commands\PublishNovaResourceTestCase;

class NovaTestingHelperServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishNovaResourceTestCase::class,
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
        ];
    }
}
