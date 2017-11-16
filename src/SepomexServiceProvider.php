<?php

namespace Aftab\Sepomex;

use Illuminate\Support\ServiceProvider;
use Aftab\Sepomex\Console\ImporterCommand;
use Aftab\Sepomex\Contracts\SepomexContract;
use Aftab\Sepomex\Repositories\CachedRepository;
use Aftab\Sepomex\Repositories\DatabaseRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * Class SepomexServiceProvider.
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
            $configPath = __DIR__.'/../config/sepomex.php';

            $this->publishes([$configPath => config_path('sepomex.php')], 'sepomex');

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

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
        $configPath = __DIR__.'/../config/sepomex.php';

        $this->mergeConfigFrom($configPath, 'sepomex');

        $this->app->singleton(SepomexContract::class, function ($app) {
            return new CachedRepository(new DatabaseRepository(), $app[CacheRepository::class]);
        });
    }
}
