<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FlignoTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-17
 */
class FlignoTest extends Command
{
    use UsesVendorPackage;

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
     * @throws PackageNotFoundException|JsonException
     */
    public function handle(): void
    {
        $this->setVendorAndPackage();

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

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return null;
    }
}
