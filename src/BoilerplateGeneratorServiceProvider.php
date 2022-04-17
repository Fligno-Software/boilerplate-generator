<?php

namespace Fligno\BoilerplateGenerator;

use Fligno\BoilerplateGenerator\Console\Commands\ClassMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\DddControllerMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\DocsGenCommand;
use Fligno\BoilerplateGenerator\Console\Commands\DtoMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeCast;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeChannel;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeComponent;
use Fligno\BoilerplateGenerator\Console\Commands\ExtendedMakeController;
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
use Fligno\BoilerplateGenerator\Console\Commands\FacadeMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoDomainCreateCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoDomainListCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCloneCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageDisableCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageEnableCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageListCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackagePublishCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageRemoveCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoTest;
use Fligno\BoilerplateGenerator\Console\Commands\GitlabCIMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\HelperMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\InterfaceMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCreateCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RepositoryMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RouteMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\ContainerMakeCommand;
use Fligno\BoilerplateGenerator\Console\Commands\TraitMakeCommand;
use Fligno\StarterKit\Providers\BaseStarterKitServiceProvider as ServiceProvider;

class BoilerplateGeneratorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ClassMakeCommand::class,
        DddControllerMakeCommand::class,
        DtoMakeCommand::class,
        DocsGenCommand::class,
        ExtendedMakeCast::class,
        ExtendedMakeChannel::class,
        ExtendedMakeCommand::class,
        ExtendedMakeComponent::class,
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
        FacadeMakeCommand::class,
        FlignoDomainCreateCommand::class,
        FlignoDomainListCommand::class,
        FlignoPackageCloneCommand::class,
        FlignoPackageCreateCommand::class,
        FlignoPackageDisableCommand::class,
        FlignoPackageEnableCommand::class,
        FlignoPackageListCommand::class,
        FlignoPackagePublishCommand::class,
        FlignoPackageRemoveCommand::class,
        FlignoTest::class,
        GitlabCIMakeCommand::class,
        HelperMakeCommand::class,
        InterfaceMakeCommand::class,
        RepositoryMakeCommand::class,
        RouteMakeCommand::class,
        ContainerMakeCommand::class,
        TraitMakeCommand::class,
    ];

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

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
        $this->publishes(
            [
                __DIR__.'/../config/boilerplate-generator.php' => config_path('boilerplate-generator.php'),
            ],
            'boilerplate-generator.config'
        );

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
