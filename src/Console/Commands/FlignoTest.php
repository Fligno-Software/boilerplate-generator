<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
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
    use UsesCommandVendorPackageDomainTrait;

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
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageOptions();
    }


    /**
     * @return int
     * @throws MissingNameArgumentException
     * @throws PackageNotFoundException
     * @throws JsonException
     */
    public function handle(): int
    {
        starterKit()->clearCache();

        $showPackageChoices = ! $this->option('packages') && ! $this->option('all');

        $this->setVendorPackageDomain($showPackageChoices);

        $testPackages = false;
        $normalTest = true;

        if ($this->option('all'))
        {
            $testPackages = true;
        }
        else if ($this->package_dir || $this->option('packages'))
        {
            $testPackages = true;
            $normalTest = false;
        }

        if ($testPackages)
        {
            if ($this->package_dir) {
                $this->executeTests($this->package_dir);
            }
            else {
                $this->getEnabledPackages()->each(function ($package_name) {
                    $this->executeTests($package_name);
                });
            }
        }

        if ($normalTest)
        {
            $this->executeTests();
        }

        return 0;
    }

    /**
     * @return array
     */
    #[Pure]
    protected function getOptions(): array
    {
        return [
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

    /**
     * @param string|null $package_name
     * @return void
     */
    public function executeTests(string $package_name = null): void
    {
        $this->info('<fg=white;bg=green>[ ONGOING ]</> Running tests for ' . ($package_name ?? 'Laravel') . '...');
        exec('php artisan test' . ($package_name ? ' packages' . '/' . $package_name : null));
    }
}
