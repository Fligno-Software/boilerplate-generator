<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCloneCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCreateCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RouteMakeCommand;
use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use JsonException;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
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
     * @var string
     */
    protected string $defaultPackage = 'Laravel';

    /**
     * @var bool
     */
    protected bool $isPackageArgument = false;

    /**
     * @param bool $has_ddd
     * @return void
     */
    public function addPackageOptions(bool $has_ddd = false): void
    {
        $this->getDefinition()->addOption(new InputOption(
            'package', null, InputOption::VALUE_OPTIONAL, 'Target package to generate the files (e.g., `vendor-name/package-name`).'
        ));

        if ($has_ddd) {
            $this->getDefinition()->addOption(new InputOption(
                'ddd', null, InputOption::VALUE_NONE, 'Follow Domain-Driven Development pattern for files generation.'
            ));
        }
    }

    /**
     * @return void
     */
    public function addPackageArguments(): void
    {
        $this->getDefinition()->addArguments([
            new InputArgument('vendor', InputArgument::REQUIRED, 'The name of the vendor.'),
            new InputArgument('package', InputArgument::REQUIRED, 'The name of the package.'),
        ]);

        $this->isPackageArgument = true;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        if ($this->isGeneratorSubclass()) {
            return [
                ['name', InputArgument::OPTIONAL, 'The name of the class'],
            ];
        }

        return parent::getArguments();
    }

    /**
     * @throws PackageNotFoundException|JsonException|MissingNameArgumentException
     */
    public function setVendorAndPackage(): void
    {
        if ($this->isGeneratorSubclass()) {
            $this->info('<fg=white;bg=green>[ ONGOING ]</> Creating ' . $this->type . ($this->getNameInput() ? ': ' . $this->getNameInput() : null));
        }

        $package = $this->hasOption('package') ? $this->option('package') : null;

        if ($this->hasArgument('vendor') && $this->hasArgument('package')) {
            $package = $this->argument('vendor') . '/' . $this->argument('package');
        }

        if (is_null($package)) {
            $package = $this->choice('Choose target package', $this->getAllPackages()->prepend($this->defaultPackage)->toArray(), 0);
        }

        $package = $package === $this->defaultPackage ? null : $package;

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

                // Check if folder exists
                if ($this instanceof FlignoPackageCreateCommand === false &&
                    $this instanceof FlignoPackageCloneCommand === false &&
                    file_exists(package_path($this->package_dir)) === false)
                {
                        if ($this->option('no-interaction')) {
                            throw new PackageNotFoundException($this->package_dir);
                        }

                        $this->error('Package not found! Please choose an existing package.');

                        if ($this->isPackageArgument) {
                            $this->input->setArgument('vendor', null);
                            $this->input->setArgument('package', null);
                        }
                        else {
                            $this->input->setOption('package', null);
                        }

                        $this->setVendorAndPackage();
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getPackageArgs(): array
    {
        $args['--package'] = $this->package_dir ?? $this->defaultPackage;

        return $args;
    }

    /**
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->package_namespace ?: parent::rootNamespace();
    }

    /***** NAME INPUT *****/

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    abstract protected function getClassType(): ?string;

    /**
     * Get the validated desired class name from the input.
     *
     * @return string
     */
    protected function getValidatedNameInput(): string
    {
        $classType = $this->getClassType();
        $name = trim($this->argument('name'));

        if ($classType) {
            return Str::of($name)->before($classType) . $classType;
        }

        return $name;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string|null
     * @throws MissingNameArgumentException
     */
    protected function getNameInput(): ?string
    {
        if ($this->isGeneratorSubclass()) {

            if ($this instanceof RouteMakeCommand) {
                return null;
            }

            if (is_null($this->argument('name')))
            {
                if ($this->option('no-interaction')) {
                    throw new MissingNameArgumentException();
                }

                $this->error('You need to specify the class name.');
                $this->input->setArgument('name', $this->ask("What's the name of the class?"));
                return $this->getNameInput();
            }

            return $this->getValidatedNameInput();
        }

        return trim($this->argument('name')) ?? '';
    }

    /**
     * @return bool
     */
    public function isGeneratorSubclass(): bool
    {
        return is_subclass_of($this, GeneratorCommand::class);
    }

    /***** Package List *****/

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

    public function getAllPackages(): Collection
    {
        $allPackages = collect();

        foreach ($this->getDirectories(package_path()) as $vendor) {
            foreach ($this->getDirectories(package_path($vendor)) as $package) {
                $allPackages->add($vendor . '/' .$package);
            }
        }

        return $allPackages;
    }

    /**
     * @throws JsonException
     */
    public function getPackageChoices(): Collection
    {
        $enabled = collect($this->getEnabledPackages())->map(function ($value) {
            return $value . ' <fg=white;bg=green>[ENABLED]</>';
        });

        $disabled = collect($this->getDisabledPackages())->map(function ($value) {
            return $value . ' <fg=white;bg=red>[DISABLED]</>';
        });

        return $enabled->merge($disabled)->prepend($this->defaultPackage);
    }

    /**
     * Get all the packages installed with Packager.
     *
     * @return Collection
     * @throws JsonException
     */
    public function getEnabledPackages(): Collection
    {
        $composerFile = json_decode(file_get_contents(base_path('composer.json')), true, 512, JSON_THROW_ON_ERROR);
        $packagesPath = base_path('packages/');
        $repositories = $composerFile['repositories'] ?? [];
        $enabledPackages = collect();
        $pattern = '{'.addslashes($packagesPath).'(.*)$}';
        foreach ($repositories as $name => $info) {
            $path = $info['url'];
            if (preg_match($pattern, $path, $match)) {
                $enabledPackages->add($match[1]);
            }
        }

        return $enabledPackages;
    }

    /**
     * @return Collection
     * @throws JsonException
     */
    public function getDisabledPackages(): Collection
    {
        return $this->getAllPackages()->diff($this->getEnabledPackages());
    }

    /**
     * @param string $directory
     * @return array|false
     */
    public function getDirectories(string $directory): bool|array
    {
        return array_values(array_diff(scandir($directory), ['..', '.']));
    }
}
