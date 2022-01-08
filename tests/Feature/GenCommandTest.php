<?php

namespace Fligno\BoilerplateGenerator\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
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

    /***** ClassMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomClassWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:class', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:class', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Class created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeCast *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCastWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:cast', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:cast', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Cast created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeChannel *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomChannelWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:channel', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:channel', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Channel created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCommandWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:command', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Command created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomCommandWithSpecifiedPackage(): void
    {
        $this->artisan('gen:command', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Command created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeComponent *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomComponentWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:component', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:component', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Component created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeController *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomControllerWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:controller', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:controller', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Controller created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeEvent *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomEventWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:event', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:event', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Event created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeException *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomExceptionWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:exception', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:exception', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Exception created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeFactory *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomFactoryWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:factory', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:factory', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Factory created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeJob *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomJobWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:job', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:job', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Job created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeListener *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomListenerWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:listener', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:listener', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Listener created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeMail *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMailWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:mail', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:mail', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Mail created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeMiddleware *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMiddlewareWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:middleware', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:middleware', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Middleware created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeMigration *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMigrationWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:migration', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Migration created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomMigrationWithSpecifiedPackage(): void
    {
        $this->artisan('gen:migration', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Migration created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeModel *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomModelWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:model', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:model', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Model created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeNotification *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomNotificationWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:notification', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:notification', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Notification created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeObserver *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomObserverWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:observer', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:observer', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Observer created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakePolicy *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomPolicyWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:policy', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:policy', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Policy created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeProvider *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomProviderWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:provider', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:provider', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Provider created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeRequest *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRequestWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:request', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:request', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Request created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeResource *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomResourceWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:resource', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:resource', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Resource created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeRule *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRuleWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:rule', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:rule', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Rule created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeSeed *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomSeedWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:seed', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Seed created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomSeedWithSpecifiedPackage(): void
    {
        $this->artisan('gen:seed', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Seed created successfully.')
            ->assertSuccessful();
    }

    /***** ExtendedMakeTest *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTestWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:test', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:test', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Test created successfully.')
            ->assertSuccessful();
    }

    /***** GitlabCiMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomGitlabCiWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:gitlab', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
            ->expectsQuestion('Choose target package', 'dummy/package')
            ->expectsOutput('Gitlab created successfully.')
            ->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomGitlabCiWithSpecifiedPackage(): void
    {
        $this->artisan('gen:gitlab', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Gitlab created successfully.')
            ->assertSuccessful();
    }

    /***** RouteMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRouteWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:routes', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
            ->expectsQuestion('Choose target package', 'dummy/package')
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
        $this->artisan('gen:routes', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Route created successfully.')
            ->assertSuccessful();
    }

    /***** InterfaceMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomInterfaceWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:interface', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:interface', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Interface created successfully.')
            ->assertSuccessful();
    }

    /***** RepositoryMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomRepositoryWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:repository', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:repository', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Repository created successfully.')
            ->assertSuccessful();
    }

    /***** TraitMakeCommand *****/

    /**
     * @return void
     *
     * @test
     */
    public function canCreateRandomTraitWithoutSpecifiedPackage(): void
    {
        $this->artisan('gen:trait', [
            'name' => 'Random/RandomWithoutSpecifiedPackage',
        ])
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
        $this->artisan('gen:trait', [
            'name' => 'Random/RandomWithSpecifiedPackage',
            '--package' => 'dummy/package'
        ])
            ->expectsOutput('Trait created successfully.')
            ->assertSuccessful();
    }

    /***** HelperMakeCommand *****/
}
