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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
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
            ->expectsOutput('Exception created successfully.')
            ->assertSuccessful();
    }
}
