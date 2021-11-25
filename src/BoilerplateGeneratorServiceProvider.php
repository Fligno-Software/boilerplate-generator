<?php

namespace Fligno\BoilerplateGenerator;

use Fligno\BoilerplateGenerator\Console\Commands\DocsGenCommand;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeCast;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeChannel;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeComponent;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeController;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeController1;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeEvent;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeException;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeFactory;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeJob;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeListener;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeMail;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeMiddleware;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeMigration;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeModel;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeNotification;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeObserver;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakePolicy;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeProvider;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeRequest;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeResource;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeRule;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeSeeder;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeTest;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoTest;
use Fligno\BoilerplateGenerator\Console\Commands\InterfaceMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\MagicStarter;
use Fligno\BoilerplateGenerator\Console\Commands\PackageMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RepositoryMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RouteMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\TraitMakeCommand;
use Illuminate\Support\ServiceProvider;

class BoilerplateGeneratorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        DocsGenCommand::class,
        ExtendedMakeCast::class,
        ExtendedMakeChannel::class,
        ExtendedMakeCommand::class,
        ExtendedMakeComponent::class,
        ExtendedMakeController1::class,
        ExtendedMakeController::class,
        ExtendedMakeEvent::class,
        ExtendedMakeException::class,
        ExtendedMakeFactory::class,
        ExtendedMakeJob::class,
        ExtendedMakeListener::class,
        ExtendedMakeMail::class,
        ExtendedMakeMiddleware::class,
        ExtendedMakeMigration::class,
        ExtendedMakeModel::class,
        ExtendedMakeNotification::class,
        ExtendedMakeObserver::class,
        ExtendedMakePolicy::class,
        ExtendedMakeProvider::class,
        ExtendedMakeRequest::class,
        ExtendedMakeResource::class,
        ExtendedMakeRule::class,
        ExtendedMakeSeeder::class,
        ExtendedMakeTest::class,
        InterfaceMakeCommand::class,
        FlignoTest::class,
        MagicStarter::class,
        PackageMakeCommand::class,
        RepositoryMakeCommand::class,
        RouteMakeCommand::class,
        TraitMakeCommand::class,
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
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
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
