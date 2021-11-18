<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FlignoTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-17
 */
class FlignoTest extends Command
{
    use UsesVendorPackageInput;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the application and package tests.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->setVendorAndPackage($this);

        $testPackages = false;
        $normalTest = true;

        if ($this->option('all'))
        {
            $testPackages = true;
        }
        else if ($this->option('packages') || $this->option('package'))
        {
            $testPackages = true;
            $normalTest = false;
        }

        if ($testPackages)
        {
            exec('php artisan test' . ' packages' . ($this->package_dir ? '/' . $this->package_dir : null));
        }

        if ($normalTest)
        {
            $this->call('test');
        }

    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return [
            ['package', null, InputOption::VALUE_REQUIRED, 'Run all tests of a specific package (e.g., `vendor-name/package-name`).'],
            ['all', 'a', InputOption::VALUE_NONE, 'Run all Laravel tests and tests within packages.'],
            ['packages', 'p', InputOption::VALUE_NONE, 'Run all tests within packages.'],
        ];
    }
}
