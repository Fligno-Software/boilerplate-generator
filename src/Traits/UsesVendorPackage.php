<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Trait UsesVendorPackage
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-11
 */
trait UsesVendorPackage
{
    /**
     * @var string|null
     */
    protected ?string $package_name = null;

    /**
     * @var string|null
     */
    protected ?string $vendor_name = null;

    /**
     * @var string|null
     */
    protected ?string $package_name_studly = null;

    /**
     * @var string|null
     */
    protected ?string $vendor_name_studly = null;

    /**
     * @var string|null
     */
    protected ?string $package_namespace = null;

    /**
     * @var string|null
     */
    protected ?string $package_dir = null;

    /**
     * @param bool $has_ddd
     * @return void
     */
    public function addPackageOptions(bool $has_ddd = false): void
    {
        $this->getDefinition()->addOption(new InputOption(
            'package', null, InputOption::VALUE_REQUIRED, 'Target package to generate the files (e.g., `vendor-name/package-name`).'
        ));

        if ($has_ddd) {
            $this->getDefinition()->addOption(new InputOption(
                'ddd', null, InputOption::VALUE_NONE, 'Follow Domain-Driven Development pattern for files generation.'
            ));
        }
    }

    public function addPackageArguments(): void
    {
        $this->getDefinition()->addArguments([
            new InputArgument('vendor', InputArgument::REQUIRED, 'The name of the vendor.'),
            new InputArgument('package', InputArgument::REQUIRED, 'The name of the package.'),
        ]);
    }

    public function setVendorAndPackage(Command $command): void
    {
        $package = $this->hasOption('package') ? $command->option('package') : null;

        if ($command->hasArgument('vendor') && $command->hasArgument('package')) {
            $package = $this->argument('vendor') . '/' . $this->argument('package');
        }

        if ($package && str_contains($package, '/')) {
            [$this->vendor_name, $this->package_name] = explode('/', $package);

            if ($this->vendor_name && $this->package_name) {
                // Formatting
                $this->vendor_name = Str::kebab($this->vendor_name);
                $this->package_name = Str::kebab($this->package_name);
                $this->vendor_name_studly = Str::studly($this->vendor_name);
                $this->package_name_studly = Str::studly($this->package_name);
                $this->package_namespace = $this->vendor_name_studly . '\\' . $this->package_name_studly . '\\';
                $this->package_dir = $this->vendor_name . '/' . $this->package_name;
            }
        }
    }

    /**
     * @return array
     */
    public function getPackageArgs(): array
    {
        $args = [];

        if ($this->vendor_name && $this->package_name) {
            $args['--package'] = $this->package_dir;
        }

        return $args;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $path = $this->package_dir ? package_app_path($this->package_dir) : $this->laravel['path'];

        return $path.'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->package_namespace ?: parent::rootNamespace();
    }
}