<?php

namespace Fligno\BoilerplateGenerator;

use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeController;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeEvent;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeMigration;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeModel;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeRequest;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeResource;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeTest;
use Fligno\BoilerplateGenerator\Console\Commands\MagicStarter;
use Fligno\BoilerplateGenerator\Console\Commands\MakePackage;
use Fligno\BoilerplateGenerator\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class BoilerplateGeneratorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ExtendedMakeController::class,
        ExtendedMakeEvent::class,
        ExtendedMakeMigration::class,
        ExtendedMakeModel::class,
        ExtendedMakeRequest::class,
        ExtendedMakeResource::class,
        ExtendedMakeTest::class,
        MagicStarter::class,
        MakePackage::class
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fligno');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'fligno');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // Register Custom Exception Handler
        if (config('boilerplate-generator.override_exception_handler')) {
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/boilerplate-generator.php', 'boilerplate-generator');

        // Register the service the package provides.

        $this->app->bind('extended-response', function ($app) {
            return new ExtendedResponse();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['extended-response'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/boilerplate-generator.php' => config_path('boilerplate-generator.php'),
        ], 'boilerplate-generator.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/fligno'),
        ], 'boilerplate-generator.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/fligno'),
        ], 'boilerplate-generator.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/fligno'),
        ], 'boilerplate-generator.views');*/

        // Registering package commands.
         $this->commands($this->commands);
    }
}
