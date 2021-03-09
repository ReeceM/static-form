<?php

namespace ReeceM\StaticForm;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use ReeceM\StaticForm\Commands\StaticFormCommand;
use ReeceM\StaticForm\Stores\FileStore;

class StaticFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('static-form.php'),
            ], 'static-form-config');

            $this->publishes([
                __DIR__ . '/../stubs/StaticFormServiceProvider.stub' => app_path('Providers/StaticFormServiceProvider.php'),
            ], 'static-form-provider');
        }

        $this->bootRoutes();
        /*
         * Optional methods to load up in the future.. offering a database instance
         */
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'static-form');

        // Register the main class to use with the facade
        $this->app->singleton('static-form', function () {
            return new StaticForm;
        });

         $this->app->bind(
            \ReeceM\StaticForm\Contracts\StaticKeyStore::class,
            function () {
                $disk = config('static-form.storage.disk');

                return new FileStore(Storage::disk($disk));
            }
        );

        // Registering package commands.
        $this->commands([
            StaticFormCommand::class
        ]);
    }

    /**
     * Boot the package routes.
     *
     * @return void
     */
    protected function bootRoutes()
    {
        Route::group([
            'prefix' => config('static-form.path'),
            'as' => 'static-form.',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        });
    }
}
