<?php
namespace Jalno\Storage\Providers;

use Jalno\Lumen\Contracts;
use Jalno\Storage\Repository;
use Illuminate\Support\ServiceProvider;

class StorageProvider extends ServiceProvider
{
	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Contracts\IStorage::class, fn($app, array $parameters) => new Repository($parameters[0]));
        $this->app->instance(Contracts\IStorage::class, Repository::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        //
	}
}
