<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandMultipleTargetsTrait;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class TestCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-17
 */
class TestCommand extends Command
{
    use UsesCommandMultipleTargetsTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bg:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the application and package tests.';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addMultipleTargetsOption();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->targets = $this->getPackageFromOptions(true);

        // check if it has default package
        $has_default_target = $this->targets && in_array($this->default_package, $this->targets);

        // show choices if neither --all or --packages is used
        if (! $this->isRootAndPackages() && ! $this->isPackagesOnly()) {
            $default_choices = $this->targets ? boilerplateGenerator()->getSummarizedPackages($this->targets)
                ->keys()
                // add back 'root' to the list of default choices if previously typed
                ->when(
                    $has_default_target,
                    fn(Collection $collection) => $collection->prepend($this->default_package)
                )
                ->toArray() :
                [];

            $this->targets = $this->choosePackageFromList(multiple: true, default_choices: $default_choices);
        }
        else {
            $this->targets = null;
        }

        // default behavior
        $test_packages = false;

        // needed checks related to default package
        $has_default_target = $this->targets && in_array($this->default_package, $this->targets); // check again
        $has_other_targets = $has_default_target ? count($this->targets) > 1 : ($this->targets && count($this->targets));

        // if packages is null or has default
        $test_root = (! $this->targets || $has_default_target) && ! $this->isPackagesOnly();

        // Decide whether to test packages
        if ($this->isRootAndPackages() || $this->isPackagesOnly() || $has_other_targets) {
            $test_packages = true;
        }

        // Actual Test Executions

        $packages_to_test = $test_packages ?
            boilerplateGenerator()->getSummarizedPackages(is_loaded: true)
            ->when($has_other_targets, fn(Collection $collection) => $collection->only($this->targets)) :
            null;

        $progress = $this->output->createProgressBar(intval($packages_to_test?->count()) + intval($test_root));

        $executeStep = function (callable $callable) use ($progress) {
            $progress->advance();
            $progress->display();
            $this->newLine(2);
            $callable();
        };

        if ($test_root) {
            $executeStep(fn() => $this->executeTests());
        }

        $packages_to_test?->each(function(array $array, string $package) use ($progress, $executeStep) {
            $executeStep(fn() => $this->executeTests($package, $array['path']));
        });

        $progress->finish();

        return 0;
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
     * @param string|null $package_path
     * @return void
     */
    public function executeTests(string $package_name = null, string $package_path = null): void
    {
        $this->ongoing('Running tests for ' . ($package_name ?? 'Laravel'));

        $test_directory = null;

        if ($package_path) {
            $package_path = Str::of($package_path)
                ->after(base_path())
                ->replace('\\','/')
                ->ltrim('/')
                ->append('/tests')
                ->jsonSerialize();

            $test_directory = '--test-directory=' . $package_path;
        }

        $command = implode(' ', ['php artisan test', $package_path, $test_directory]);

        $this->ongoing('Running command: ' . $command, false);

        exec($command, $this->output);
    }

    /**
     * @return bool
     */
    protected function isRootAndPackages(): bool
    {
        return $this->option('all');
    }

    protected function isPackagesOnly(): bool
    {
        return $this->option('packages');
    }
}
