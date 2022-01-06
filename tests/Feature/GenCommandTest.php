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
}
