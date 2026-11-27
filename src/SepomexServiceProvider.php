<?php

namespace Wafto\Sepomex;

use Illuminate\Support\ServiceProvider;
use Wafto\Sepomex\Console\ImporterCommand;
use Wafto\Sepomex\Contracts\SepomexContract;
use Wafto\Sepomex\Models\Sepomex;
use Wafto\Sepomex\Repositories\CachedRepository;
use Wafto\Sepomex\Repositories\DatabaseRepository;

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

        $this->app->singleton(CachedRepository::class, function ($app) {
            $ttl = $app['config']->get('sepomex.cache.ttl');

            return new CachedRepository(
                new DatabaseRepository(new Sepomex),
                $app['cache']->store($app['config']->get('sepomex.cache.store')),
                $ttl === null ? null : (int) $ttl,
            );
        });

        $this->app->alias(CachedRepository::class, SepomexContract::class);
    }
}
