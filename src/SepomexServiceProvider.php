<?php

namespace Aftab\Sepomex;

use Illuminate\Support\ServiceProvider;
use Aftab\Sepomex\Console\ImporterCommand;

/**
 * Class SepomexServiceProvider
 * @package Aftab\Sepomex
 */
class SepomexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__ . '/../config/sepomex.php';

            $this->publishes([$configPath => config_path('sepomex.php')], 'sepomex');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->commands([
                ImporterCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/sepomex.php';

        $this->mergeConfigFrom($configPath, 'sepomex');
    }
}
