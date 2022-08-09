<?php

namespace Fligno\BoilerplateGenerator\Feature;

use Tests\TestCase;

/**
 * Class GenCommandTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class GenCommandTest extends TestCase
{
    /**
     * @return void
     *
     * @test
     */
    public function canCreateDummyPackage(): void
    {
        if (file_exists(package_path('dummy/package'))) {
            // Delete Dummy Package First
            exec('php artisan fligno:package:remove dummy package --no-interaction');
        }

        // Create Dummy Package
        exec('php artisan fligno:package:create dummy package --no-interaction', $output, $code);

        $this->assertSame($code, 0);
    }

    /*****
     * ClassMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomClassWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:class',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Class created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomClassWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:class',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Class created successfully.')
            ->assertSuccessful();
    }

    /*****
     * DddActionMakeCommand
     *****/

    /*****
     * DddControllerMakeCommand
     *****/

    /*****
     * DtoMakeCommand
     *****/

    /*****
     * DocsGenCommand
     *****/

    /*****
     * ExtendedMakeCast
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCastWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:cast',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Cast created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCastWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:cast',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Cast created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeChannel
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomChannelWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:channel',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Channel created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomChannelWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:channel',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Channel created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCommandWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:command',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Console command created successfully.')
            ->expectsOutput('Test created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCommandWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:command',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Console command created successfully.')
            ->expectsOutput('Test created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeComponent
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomComponentWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:component',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Component created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomComponentWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:component',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Component created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeController
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomControllerWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:controller',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Controller created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomControllerWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:controller',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Controller created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeEvent
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomEventWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:event',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Event created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomEventWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:event',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Event created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeException
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomExceptionWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:exception',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Exception created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomExceptionWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:exception',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Exception created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeFactory
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomFactoryWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:factory',
            [
                'name' => 'RandomOne',
                '--model' => 'User',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Factory created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomFactoryWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:factory',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
                '--model' => 'User',
            ]
        )
            ->expectsOutput('Factory created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeJob
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomJobWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:job',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Job created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomJobWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:job',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Job created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeListener
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomListenerWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:listener',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Listener created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomListenerWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:listener',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Listener created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeMail
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMailWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:mail',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Mail created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMailWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:mail',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Mail created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeMiddleware
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMiddlewareWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:middleware',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Middleware created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMiddlewareWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:middleware',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeMigration
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMigrationWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:migration',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMigrationWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:migration',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeModel
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomModelWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:model',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Model created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomModelWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:model',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Model created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateSomethingModelWithAllOption(): void
    {
        $this->artisan(
            'gen:model',
            [
                'name' => 'UserDataFactory',
                '--package' => 'dummy/package',
                '--all' => true,
            ]
        )
            ->expectsOutput('Model created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeNotification
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomNotificationWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:notification',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Notification created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomNotificationWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:notification',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Notification created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeObserver
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomObserverWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:observer',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Observer created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomObserverWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:observer',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Observer created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakePolicy
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomPolicyWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:policy',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Policy created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomPolicyWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:policy',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Policy created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeProvider
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomProviderWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:provider',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Provider created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomProviderWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:provider',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Provider created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeRequest
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRequestWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:request',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Request created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRequestWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:request',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Request created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeResource
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomResourceWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:resource',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Resource created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomResourceWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:resource',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Resource created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeRule
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRuleWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:rule',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Rule created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRuleWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:rule',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Rule created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeSeeder
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomSeedWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:seeder',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Seeder created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomSeedWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:seeder',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Seeder created successfully.')
            ->assertSuccessful();
    }

    /*****
     * ExtendedMakeTest
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTestWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:test',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Test created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTestWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:test',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Test created successfully.')
            ->assertSuccessful();
    }

    /*****
     * GitlabCiMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomGitlabCiWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:gitlab',
            [
                '--force' => true,
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Gitlab file created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomGitlabCiWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:gitlab',
            [
                '--package' => 'dummy/package',
                '--force' => true,
            ]
        )
            ->expectsOutput('Gitlab file created successfully.')
            ->assertSuccessful();
    }

    /*****
     * InterfaceMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomInterfaceWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:interface',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Interface created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomInterfaceWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:interface',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Interface created successfully.')
            ->assertSuccessful();
    }

    /*****
     * RepositoryMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRepositoryWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:repository',
            [
                'name' => 'RandomOne',
                '--model' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Repository created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRepositoryWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:repository',
            [
                'name' => 'RandomTwo',
                '--model' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Repository created successfully.')
            ->assertSuccessful();
    }

    /*****
     * RouteMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRouteWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:routes',
            [
                '--force' => true,
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Route created successfully.')
            ->expectsOutput('Route created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRouteWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:routes',
            [
                '--package' => 'dummy/package',
                '--force' => true,
            ]
        )
            ->expectsOutput('Route created successfully.')
            ->expectsOutput('Route created successfully.')
            ->assertSuccessful();
    }

    /*****
     * TraitMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTraitWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:trait',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Trait created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTraitWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:trait',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Trait created successfully.')
            ->assertSuccessful();
    }

    // Service Container

    /*****
     * ContainerMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomContainerWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:container',
            [
                'name' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Service Container created successfully.')
            ->expectsOutput('Helper created successfully.')
            ->expectsOutput('Facade created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomContainerWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:container',
            [
                'name' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Service Container created successfully.')
            ->expectsOutput('Helper created successfully.')
            ->expectsOutput('Facade created successfully.')
            ->assertSuccessful();
    }

    /*****
     * HelperMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomHelperWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:helper',
            [
                'name' => 'OtherOne',
                '--container' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Helper created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomHelperWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:helper',
            [
                'name' => 'OtherTwo',
                '--container' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Helper created successfully.')
            ->assertSuccessful();
    }

    /*****
     * FacadeMakeCommand
     *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomFacadeWithoutSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:facade',
            [
                'name' => 'OtherOne',
                '--container' => 'RandomOne',
            ]
        )
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Facade created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomFacadeWithSpecifiedPackage(): void
    {
        $this->artisan(
            'gen:facade',
            [
                'name' => 'OtherTwo',
                '--container' => 'RandomTwo',
                '--package' => 'dummy/package',
            ]
        )
            ->expectsOutput('Facade created successfully.')
            ->assertSuccessful();
    }
}
