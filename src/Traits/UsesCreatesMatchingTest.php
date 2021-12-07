<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Illuminate\Support\Str;

/**
 * Trait UsesCreatesMatchingTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesCreatesMatchingTest
{
    use UsesVendorPackage;

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return void
     */
    protected function handleTestCreation($path): void
    {
        $appPath = $this->package_dir ? package_app_path($this->package_dir) : $this->laravel['path'];

        $args = $this->getPackageArgs();
        $args['name'] = Str::of($path)->after($appPath)->beforeLast('.php')->append('Test')->replace('\\', '/');
        $args['--pest'] = $this->option('pest');

        $this->call('gen:test', $args);
    }
}
