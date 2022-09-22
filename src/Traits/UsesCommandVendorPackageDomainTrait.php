<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Fligno\BoilerplateGenerator\Console\Commands\PackageCloneCommand;
use Fligno\BoilerplateGenerator\Console\Commands\PackageCreateCommand;
use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesCommandVendorPackageDomainTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-11
 */
trait UsesCommandVendorPackageDomainTrait
{
    use UsesCommandDomainTrait;

    /***** PACKAGE RELATED FIELDS *****/

    /**
     * @var string|null
     */
    protected ?string $package_option_argument_name = 'package';

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
     * @var bool
     */
    protected bool $is_package_namespace_disabled = false;

    /**
     * @var string|null
     */
    protected ?string $package_dir = null;

    /**
     * @var string
     */
    protected string $default_package = 'root';

    /**
     * @var bool
     */
    protected bool $is_package_argument = false;

    /***** OTHER FIELDS *****/

    /**
     * @var Collection|null
     */
    protected ?Collection $moreReplaceNamespace = null;

    /**
     * @param  bool  $has_force
     * @param  bool  $ddd_enabled
     * @param  bool  $has_force_domain
     * @param  string  $option_name
     * @return void
     */
    public function addPackageDomainOptions(
        bool $has_force = false,
        bool $ddd_enabled = true,
        bool $has_force_domain = true,
        string $option_name = 'package',
    ): void {
        $this->package_option_argument_name = $option_name;

        $this->getDefinition()->addOption(
            new InputOption(
                $this->package_option_argument_name,
                null,
                InputOption::VALUE_OPTIONAL,
                'Target package to generate the files (e.g., `vendor-name/package-name`).'
            )
        );

        // Add domain options
        if ($ddd_enabled) {
            $this->addDomainOptions($has_force_domain);
        }

        if ($has_force && $this->getDefinition()->hasOption('force') === false) {
            $this->getDefinition()->addOption(
                new InputOption(
                    'force',
                    'f',
                    InputOption::VALUE_NONE,
                    'Overwrite file if exists.'
                )
            );
        }

        $this->is_package_argument = false;
    }

    /**
     * @param  bool  $isRequired
     * @param  string  $argument_name
     * @return void
     */
    public function addPackageArguments(bool $isRequired = true, string $argument_name = 'package'): void
    {
        $this->package_option_argument_name = $argument_name;

        $mode = $isRequired ? InputArgument::REQUIRED : InputArgument::OPTIONAL;

        $this->getDefinition()->addArguments(
            [
                new InputArgument($this->package_option_argument_name, $mode, 'The name of the package, e.g., `vendor-name/package-name`.'),
            ]
        );

        $this->is_package_argument = true;
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
     * @param  bool  $show_package_choices
     * @param  bool  $show_domain_choices
     * @param  bool  $show_default_package
     * @return void
     *
     * @throws MissingNameArgumentException
     * @throws PackageNotFoundException
     */
    public function setVendorPackageDomain(
        bool $show_package_choices = true,
        bool $show_domain_choices = true,
        bool $show_default_package = true
    ): void {
        // Set Author Information
        $this->setAuthorInformationOnStub();

        if ($this->isGeneratorSubclass()) {
            $this->note($this->type.($this->getNameInput() ? ': '.$this->getNameInput() : null), 'ONGOING');
        }

        $package = $this->getPackageFromOptions() ?: $this->getPackageFromArguments();

        if (! $package && $show_package_choices) {
            $package = $this->choosePackageFromList($show_default_package);
        }

        if ($package === $this->default_package) {
            $package = null;
        }

        if ($package && str_contains($package, '/')) {
            [$this->vendor_name, $this->package_name] = explode('/', $package);

            if ($this->vendor_name && $this->package_name) {
                // Formatting
                $this->vendor_name = Str::kebab($this->vendor_name);
                $this->package_name = Str::kebab($this->package_name);
                $this->vendor_name_studly = Str::studly($this->vendor_name);
                $this->package_name_studly = Str::studly($this->package_name);
                $this->package_dir = $this->vendor_name.'/'.$this->package_name;
                $this->package_namespace = $this->vendor_name_studly.'\\'.$this->package_name_studly.'\\';

                // Check if folder exists
                if (
                    ! $this instanceof PackageCreateCommand &&
                    ! $this instanceof PackageCloneCommand &&
                    ! file_exists(package_domain_path($this->package_dir))
                ) {
                    if ($this->isNoInteraction()) {
                        throw new PackageNotFoundException($this->package_dir);
                    }

                    $this->error('Package not found! Please choose an existing package.');

                    if ($this->is_package_argument) {
                        $this->input->setArgument($this->package_option_argument_name, null);
                    } else {
                        $this->input->setOption($this->package_option_argument_name, null);
                    }

                    $this->setVendorPackageDomain();
                }
            }
        }

        if ($show_domain_choices) {
            $this->setDomainFieldsFromOptions(
                $this->package_option_argument_name,
                $this->package_dir,
                $this->package_namespace
            );
        }
    }

    /**
     * @return bool
     */
    public function hasPackageAsOption(): bool
    {
        return $this->hasOption($this->package_option_argument_name);
    }

    /**
     * @param  bool  $multiple
     * @return string|array|null
     */
    public function getPackageFromOptions(bool $multiple = false): string|array|null
    {
        if ($this->hasPackageAsOption() &&
            $target = trim($this->option($this->package_option_argument_name), '/')) {
            return $multiple ? explode(',', $target) : $target;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasPackageAsArgument(): bool
    {
        return $this->hasArgument($this->package_option_argument_name);
    }

    /**
     * @return string|null
     */
    public function getPackageFromArguments(): string|null
    {
        return $this->hasPackageAsArgument() ?
            trim($this->argument($this->package_option_argument_name), '/') :
            null;
    }

    /**
     * @param  bool  $show_default_package
     * @param  bool  $multiple
     * @param  array  $default_choices
     * @return array|string|null
     */
    public function choosePackageFromList(bool $show_default_package = true, bool $multiple = false, array $default_choices = []): array|string|null
    {
        if (($choices = boilerplateGenerator()->getLocalPackages()->keys()) && $choices->count()) {
            $choices = $choices
                ->when($show_default_package, fn ($choices) => $choices->prepend($this->default_package))
                ->toArray();

            if ($show_default_package && ! count($default_choices)) {
                $default_choices[] = $this->default_package;
            }

            $default = null;

            if (count($default_choices)) {
                $default = collect($default_choices)
                    ->map(function ($item) use ($choices) {
                        return array_search($item, $choices);
                    })
                    ->filter(fn ($item) => $item !== false)
                    ->implode(',');
            }

            return $this->choice('Choose '.($multiple ? 'target' : 'targets'), $choices, $default, null, $multiple);
        }

        return null;
    }

    /**
     * @param  bool  $withDomain
     * @return array
     */
    public function getPackageArgs(bool $withDomain = true): array
    {
        $args['--'.$this->package_option_argument_name] = $this->package_dir ?? $this->default_package;

        if ($withDomain) {
            $args = array_merge($args, $this->getDomainArgs());
        }

        return $args;
    }

    /**
     * @return string
     */
    protected function rootNamespace(): string
    {
        if ($this->is_package_namespace_disabled || ! $this->getPackageDomainNamespace()) {
            return parent::rootNamespace();
        }

        return $this->getPackageDomainNamespace();
    }

    /*****
     * NAME INPUT
     *****/

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
            return Str::of($name)->before($classType).$classType;
        }

        return $name;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string|null
     *
     * @throws MissingNameArgumentException
     */
    protected function getNameInput(): ?string
    {
        if ($this->isGeneratorSubclass()) {
            if (is_null($this->argument('name'))) {
                if ($this->isNoInteraction()) {
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
    public function isNoInteraction(): bool
    {
        return $this->option('no-interaction');
    }

    /**
     * @return bool
     */
    public function isGeneratorSubclass(): bool
    {
        return is_subclass_of($this, GeneratorCommand::class);
    }

    /***** PACKAGE LIST *****/

    /**
     * @return string|null
     */
    protected function getPackageDomainNamespace(): ?string
    {
        return $this->domain_namespace ?? $this->package_namespace;
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        return collect([
            $this->package_dir ? package_domain_app_path($this->package_dir) : app_path(),
            $this->domain_dir,
            $this->domain_dir ? 'src' : null,
        ])
            ->filter()
            ->implode('/');
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

        $path = $this->getPackageDomainFullPath();

        return $path.DIRECTORY_SEPARATOR.str_replace('\\', '/', $name).'.php';
    }

    /***** STUB REPLACEMENT LOGIC *****/

    /**
     * @param  Collection|array  $more
     * @return Collection
     */
    public function addMoreReplaceNamespace(Collection|array $more): Collection
    {
        if (count($more)) {
            if (! $this->moreReplaceNamespace) {
                $this->moreReplaceNamespace = collect($more);
            } else {
                $this->moreReplaceNamespace = $this->moreReplaceNamespace->merge($more);
            }
        }

        return $this->moreReplaceNamespace ?? collect();
    }

    /**
     * @param  string  $namespacedClass
     * @param  string|null  $classType
     * @return Collection
     */
    protected function addMoreCasedReplaceNamespace(string $namespacedClass, string $classType = null): Collection
    {
        $class = Str::of($namespacedClass)->afterLast('\\')->studly();

        $key = $classType ?? $class->jsonSerialize();

        $more = collect(
            [
                'Namespaced'.$key => $namespacedClass,
                $key.'Class' => $class,
                $key.'Snake' => $class->snake(),
                $key.'Slug' => $class->snake('-'),
                $key.'Camel' => $class->camel(),
            ]
        );

        $this->addMoreReplaceNamespace($more);

        return $more;
    }

    /**
     * Overriding to inject more namespace.
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): static
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}'],
        ];

        $replacements = [
            $this->getNamespace($name),
            $this->getRootNamespaceDuringReplaceNamespace(),
            $this->userProviderModel(),
        ];

        if ($this->moreReplaceNamespace && Arr::isAssoc($this->moreReplaceNamespace->toArray())) {
            $this->moreReplaceNamespace->each(
                function ($item, $key) use (&$searches, &$replacements) {
                    $item = trim($item);
                    $key = trim($key);

                    if ($item && $key) {
                        $searches[0][] = Str::studly($key);
                        $searches[1][] = '{{ '.Str::camel($key).' }}';
                        $searches[2][] = '{{'.Str::camel($key).'}}';
                        $replacements[] = $item;
                    }
                }
            );
        }

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                $replacements,
                $stub
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getRootNamespaceDuringReplaceNamespace(): string
    {
        return $this->rootNamespace();
    }

    /**
     * @param  string  $classNamespace
     * @return array|string
     */
    protected function cleanClassNamespace(string $classNamespace): array|string
    {
        $classNamespace = ltrim($classNamespace, '\\/');

        return str_replace('/', '\\', $classNamespace);
    }

    /**
     * @return bool
     */
    protected function shouldOverwrite(): bool
    {
        return $this->hasOption('force') && $this->option('force');
    }

    /*****
     * ELOQUENT MODEL RELATED
     *****/

    /**
     * @param  string  $option_name
     * @return string
     */
    protected function getModelClass(string $option_name): string
    {
        $modelClass = $this->parseModel($this->option($option_name));

        if (! class_exists($modelClass)) {
            // ask if the model should be generated instead
            if ($this->confirm("$modelClass model does not exist. Do you want to generate it?", true)) {
                $args = $this->getPackageArgs();
                $args['name'] = $modelClass;

                $this->call('bg:make:model', $args);
            }

            // or maybe choose from possible Eloquent models
            else {
                $alternativeModels = collect();

                if (($packageDomainFullPath = $this->getPackageDomainFullPath()) !== app_path()) {
                    if (file_exists($temp = $packageDomainFullPath.'/Models')) {
                        $alternativeModels = $alternativeModels->merge(collect_classes_from_path($temp)?->values());
                    }

                    if ($this->package_dir &&
                        ($temp = package_domain_app_path($this->package_dir)) &&
                        $temp !== $packageDomainFullPath &&
                        file_exists($temp .= '/Models')
                    ) {
                        $alternativeModels = $alternativeModels->merge(collect_classes_from_path($temp)?->values());
                    }
                }

                $alternativeModels = $alternativeModels
                    ->merge(collect_classes_from_path(app_path('Models'))?->values());

                $defaultAlternativeModel = 'none';

                $modelClass = $this->choice(
                    'Choose alternative '.($option_name === 'parent' ? $option_name.' ' : null).'model',
                    $alternativeModels->prepend($defaultAlternativeModel)->toArray(),
                    0
                );

                $modelClass = $modelClass === $defaultAlternativeModel ? null : $modelClass;

                $this->input->setOption($option_name, $modelClass);
            }
        }

        return $modelClass;
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function parseModel($model): string
    {
        if (preg_match('([^A-Za-z\d_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /***** TEST RELATED *****/

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return void
     */
    protected function handleTestCreation($path): void
    {
        $app_path = $this->package_dir ? package_domain_app_path($this->package_dir) : $this->laravel['path'];

        $name = Str::of($path)
            ->after($app_path)
            ->beforeLast('.php');

        $args['name'] = $name->append('Test')
            ->replace('\\', '/')
            ->ltrim('/')
            ->jsonSerialize();

        $args['--no-interaction'] = true;

        if ($this->option('pest') || boilerplateGenerator()->isPestEnabled()) {
            if ($this->package_dir) {
                $args['--test-directory'] = Str::of(package_domain_tests_path($this->package_dir))
                    ->after(base_path())
                    ->replace('\\', '/')
                    ->ltrim('/')
                    ->jsonSerialize();
            }

            // Generate Pest Test
            $this->call('pest:test', $args);

            // Generate Dataset
            $args['name'] = $name->replace('\\', '/')->afterLast('/')->jsonSerialize();
            $this->call('pest:dataset', $args);
        } else {
            $this->call('bg:make:test', array_merge($args, $this->getPackageArgs()));
        }
    }

    /***** AUTHOR INFORMATION FOR FILE GENERATION *****/

    /**
     * @return void
     */
    public function setAuthorInformationOnStub(): void
    {
        $this->addMoreReplaceNamespace(
            [
                'authorName' => boilerplateGenerator()->getAuthorName(),
                'authorEmail' => boilerplateGenerator()->getAuthorEmail(),
            ]
        );
    }
}
