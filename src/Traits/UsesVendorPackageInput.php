<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Illuminate\Support\Str;

/**
 * Trait UsesVendorPackageInput
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-11
 */
trait UsesVendorPackageInput
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
    protected ?string $package_path = null;

    /**
     * @var array|array[]
     */
    protected array $default_package_options = [
        ['package', null, InputOption::VALUE_OPTIONAL, 'Target package to generate the files (e.g., `vendor-name/package-name`).'],
        ['ddd', null, InputOption::VALUE_NONE, 'Follow Domain-Driven Development pattern for files generation.'],
    ];

    public function setVendorAndPackage(Command $command): void
    {
        $package = $this->hasOption('package') ? $command->option('package') : null;

        if ($command->hasArgument('vendor') && $command->hasArgument('package')) {
            $package = $this->argument('vendor') . '/' . $this->argument('package');
        }

        if ($package && strpos($package, '/') !== FALSE) {
            [$this->vendor_name, $this->package_name] = explode('/', $package);

            if ($this->vendor_name && $this->package_name) {
                // Formatting
                $this->vendor_name = Str::kebab($this->vendor_name);
                $this->package_name = Str::kebab($this->package_name);
                $this->vendor_name_studly = Str::studly($this->vendor_name);
                $this->package_name_studly = Str::studly($this->package_name);
                $this->package_namespace = $this->vendor_name_studly . '\\' . $this->package_name_studly . '\\';
                $this->package_path = $this->vendor_name . '/' . $this->package_name;
            }
        }

//        $this->info('VENDOR: ' . $this->vendor_name);
//        $this->info('PACKAGE: ' . $this->package_name);
    }

    /**
     * @return array
     */
    public function getInitialArgs(): array
    {
        $args = [];

        if ($this->vendor_name && $this->package_name) {
            $args['--package'] = $this->package_path;
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

        $path = $this->package_path ? package_app_path($this->package_path) : $this->laravel['path'];

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
