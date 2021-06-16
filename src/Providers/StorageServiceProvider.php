<?php
namespace Jalno\Storage\Providers;

use Jalno\Lumen\Contracts;
use Jalno\Storage\Repository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class StorageServiceProvider extends ServiceProvider implements DeferrableProvider
{
	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Contracts\IStorage::class, fn($app, array $parameters) => new Repository($parameters[0]));
    }

    public function provides()
    {
        return [Contracts\IStorage::class];
    }
}
