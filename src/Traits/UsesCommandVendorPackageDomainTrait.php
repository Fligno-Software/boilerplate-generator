<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCloneCommand;
use Fligno\BoilerplateGenerator\Console\Commands\FlignoPackageCreateCommand;
use Fligno\BoilerplateGenerator\Console\Commands\RouteMakeCommand;
use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\StarterKit\Traits\UsesCommandCustomMessagesTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use JsonException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

/**
 * Trait UsesCommandVendorPackageDomainTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2021-11-11
 */
trait UsesCommandVendorPackageDomainTrait
{
    use UsesCommandCustomMessagesTrait;

    /*****
     * PACKAGE RELATED FIELDS
     *****/

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
    protected string $default_package = 'none';

    /**
     * @var bool
     */
    protected bool $is_package_argument = false;

    /*****
     * DOMAIN RELATED FIELDS
     *****/

    /**
     * @var string|null
     */
    protected ?string $domain_name = null;

    /**
     * @var string|null
     */
    protected ?string $domain_dir = null;

    /**
     * @var string|null
     */
    protected ?string $domain_namespace = null;

    /**
     * @var string
     */
    protected string $default_domain = 'none';

    /**
     * @var bool
     */
    protected bool $ddd_enabled = true;

    /*****
     * OTHER FIELDS
     *****/

    /**
     * @var Collection|null
     */
    protected ?Collection $moreReplaceNamespace = null;

    /**
     * @param  bool $ddd_enabled
     * @param  bool $has_force
     * @param  bool $has_force_domain
     * @return void
     */
    public function addPackageOptions(
        bool $ddd_enabled = true,
        bool $has_force = false,
        bool $has_force_domain = true
    ): void {
        $this->getDefinition()->addOption(
            new InputOption(
                'package',
                null,
                InputOption::VALUE_OPTIONAL,
                'Target package to generate the files (e.g., `vendor-name/package-name`).'
            )
        );

        if ($this->ddd_enabled = $ddd_enabled) {
            $this->getDefinition()->addOption(
                new InputOption(
                    'domain',
                    'd',
                    InputOption::VALUE_OPTIONAL,
                    'Domain or module name'
                )
            );
            if ($has_force_domain) {
                $this->getDefinition()->addOption(
                    new InputOption(
                        'force-domain',
                        null,
                        InputOption::VALUE_NONE,
                        'Create domain if does not exist.'
                    )
                );
            }
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
    }

    /**
     * @param  bool $isRequired
     * @return void
     */
    public function addPackageArguments(bool $isRequired = true): void
    {
        $mode = $isRequired ? InputArgument::REQUIRED : InputArgument::OPTIONAL;

        $this->getDefinition()->addArguments(
            [
                new InputArgument('vendor', $mode, 'The name of the vendor.'),
                new InputArgument('package', $mode, 'The name of the package.'),
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
     * @param  bool $showPackageChoices
     * @param  bool $showDomainChoices
     * @param  bool $showDefaultPackage
     * @return void
     * @throws MissingNameArgumentException
     * @throws PackageNotFoundException
     */
    public function setVendorPackageDomain(
        bool $showPackageChoices = true,
        bool $showDomainChoices = true,
        bool $showDefaultPackage = true
    ): void {
        if ($this->isGeneratorSubclass()) {
            $this->note($this->type . ($this->getNameInput() ? ': ' . $this->getNameInput() : null), 'ONGOING');
        }

        $package = $this->hasOption('package') ? $this->option('package') : null;

        if ($this->hasArgument('vendor') && $this->hasArgument('package')) {
            $package = $this->argument('vendor') . '/' . $this->argument('package');
        }

        $package = trim($package, '/');

        if ($showPackageChoices && ! $package && ($choices = $this->getAllPackages()) && $choices->count()) {
            $choices = $choices->when($showDefaultPackage, fn ($choices) => $choices->prepend($this->default_package));
            $package = $this->choice('Choose target package', $choices->toArray(), 0);
        }

        $package = $package === $this->default_package ? null : $package;

        if ($package && str_contains($package, '/')) {
            [$this->vendor_name, $this->package_name] = explode('/', $package);

            if ($this->vendor_name && $this->package_name) {
                // Formatting
                $this->vendor_name = Str::kebab($this->vendor_name);
                $this->package_name = Str::kebab($this->package_name);
                $this->vendor_name_studly = Str::studly($this->vendor_name);
                $this->package_name_studly = Str::studly($this->package_name);
                $this->package_dir = $this->vendor_name . '/' . $this->package_name;
                $this->package_namespace = $this->vendor_name_studly . '\\' . $this->package_name_studly . '\\';

                // Check if folder exists
                if (
                    ! $this instanceof FlignoPackageCreateCommand &&
                    ! $this instanceof FlignoPackageCloneCommand &&
                    ! file_exists(package_path($this->package_dir))
                ) {
                    if ($this->isNoInteraction()) {
                        throw new PackageNotFoundException($this->package_dir);
                    }

                    $this->error('Package not found! Please choose an existing package.');

                    if ($this->is_package_argument) {
                        $this->input->setArgument('vendor', null);
                        $this->input->setArgument('package', null);
                    } else {
                        $this->input->setOption('package', null);
                    }

                    $this->setVendorPackageDomain();
                }
            }
        }

        if ($showDomainChoices && $this->domain_name = $this->getDomainFromOptions()) {
            $this->domain_dir = 'Domains/' . $this->domain_name;
            $this->domain_namespace = ($this->package_namespace ?: 'App\\') . 'Domains\\' . $this->domain_name . '\\';
        }
    }

    /**
     * @return array|bool|string|null
     */
    public function getDomainFromOptions(): bool|array|string|null
    {
        $domain = $this->hasOption('domain') ? trim($this->option('domain')) : null;

        if ($domain === $this->default_domain) {
            return null;
        }

        $domain = $domain ? Str::studly($domain) : null;
        $domains = $this->getAllDomains();

        if ($this->ddd_enabled && ($domain || $domains)) {
            $createNewDomain = function () use ($domain, $domains, &$createNewDomain) {
                $domain = trim($this->ask('Enter new domain name', $domain));

                if ($domain === $this->default_domain) {
                    $domain = null;
                }

                $domain = $domain ? Str::studly($domain) : null;

                // create domain if it does not exist
                if ($domain) {
                    if (! $this instanceof RouteMakeCommand && ! ($domains?->contains($domain))) {
                        $args = $this->getPackageArgs(false);
                        $args['name'] = $domain;
                        $this->call('fligno:domain:create', $args);
                    }
                    return $domain;
                }

                $this->error('Failed to create new domain.');

                return $createNewDomain();
            };

            $chooseFromDomains = function () use ($domains) {
                if ($domains &&
                    ($domain = $this->choice(
                        'Choose a domain',
                        $domains->prepend($this->default_domain)->toArray(),
                        0
                    ))
                ) {
                    if ($domain === $this->default_domain) {
                        return null;
                    }
                    return $domain;
                }

                return null;
            };

            // domain IS NOT NULL and domain list IS NOT EMPTY
            if ($domain && $domains) {
                if (($this instanceof RouteMakeCommand && $this->shouldCreateDomain()) || $domains->contains($domain)) {
                    return $domain;
                }

                $choice = $this->choice(
                    'Choose what to do',
                    [
                        'create new domain',
                        'choose from domains'
                    ],
                    0
                );

                if ($choice === 'create new domain') {
                    return $createNewDomain();
                }

                return $chooseFromDomains();
            }

            // domain IS NULL and domain list IS NOT EMPTY
            if ($domains) {
                return $chooseFromDomains();
            }

            // domain IS NOT NULL and domain list IS EMPTY
            return $createNewDomain();
        }

        return null;
    }

    /**
     * @param  bool $withDomain
     * @return array
     */
    public function getPackageArgs(bool $withDomain = true): array
    {
        $args['--package'] = $this->package_dir ?? $this->default_package;

        if ($withDomain && $this->domain_name) {
            $args['--domain'] = $this->domain_name;
            $args['--force-domain'] = $this->shouldCreateDomain();
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

    /*****
     * PACKAGE LIST
     *****/

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
        return ($this->package_dir ? package_app_path($this->package_dir) :
                app_path()) . ($this->domain_dir ? '/' . $this->domain_dir : null);
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $path = $this->getPackageDomainFullPath();

        return $path.DIRECTORY_SEPARATOR.str_replace('\\', '/', $name).'.php';
    }

    /**
     * @return Collection
     */
    public function getAllPackages(): Collection
    {
        $allPackages = collect();

        if (file_exists(package_path())) {
            collect_files_or_directories(package_path(), true, false)?->each(
                function ($vendor) use ($allPackages) {
                    collect_files_or_directories(package_path($vendor), true, false)?->each(
                        function ($package) use ($allPackages, $vendor) {
                            $allPackages->add($vendor . '/' .$package);
                        }
                    );
                }
            );
        }

        return $allPackages;
    }

    /**
     * @throws JsonException
     */
    public function getPackageChoices(): Collection
    {
        $enabled = collect($this->getEnabledPackages())->map(
            function ($value) {
                return $value . ' <fg=white;bg=green>[ENABLED]</>';
            }
        );

        $disabled = collect($this->collectDisabledPackages())->map(
            function ($value) {
                return $value . ' <fg=white;bg=red>[DISABLED]</>';
            }
        );

        return $enabled->merge($disabled)->prepend($this->default_package);
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function getPackagesRows(): array
    {
        $enabled = collect($this->getEnabledPackages())->map(
            function ($value) {
                return [ $value, '<fg=white;bg=green>[ ENABLED ]</>' ];
            }
        );

        $disabled = collect($this->collectDisabledPackages())->map(
            function ($value) {
                return [ $value, '<fg=white;bg=red>[ DISABLED ]</>' ];
            }
        );

        return $enabled->merge($disabled)->toArray();
    }

    /**
     * Get all the packages installed with Package.
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
    public function collectDisabledPackages(): Collection
    {
        return $this->getAllPackages()->diff($this->getEnabledPackages());
    }

    /*****
     * DOMAINS LIST
     *****/

    /**
     * @param  bool $prependDirectory
     * @return Collection|null
     */
    public function getAllDomains(bool $prependDirectory = false): ?Collection
    {
        $path = $this->package_dir ? package_path($this->package_dir) : app_path();

        if ($domainsPath = guess_file_or_directory_path($path, 'Domains')) {
            $result = collect_files_or_directories($domainsPath, true, false, $prependDirectory);

            return $result?->isNotEmpty() ? $result : null;
        }

        return null;
    }

    /*****
     * STUB REPLACEMENT LOGIC
     *****/

    /**
     * @param  Collection|array $more
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
     * @param  string      $namespacedClass
     * @param  string|null $classType
     * @return Collection
     */
    protected function addMoreCasedReplaceNamespace(string $namespacedClass, string $classType = null): Collection
    {
        $class = Str::of($namespacedClass)->afterLast('\\')->studly();

        $key = $classType ?? $class->jsonSerialize();

        $more = collect(
            [
            'Namespaced' . $key => $namespacedClass,
            $key . 'Class' => $class,
            $key . 'Snake' => $class->snake(),
            $key . 'Slug' => $class->snake('-'),
            $key . 'Camel' => $class->camel(),
            ]
        );

        $this->addMoreReplaceNamespace($more);

        return $more;
    }

    /**
     * Overriding to inject more namespace.
     * Replace the namespace for the given stub.
     *
     * @param  string $stub
     * @param  string $name
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
            $this->userProviderModel()
        ];

        if ($this->moreReplaceNamespace && Arr::isAssoc($this->moreReplaceNamespace->toArray())) {
            $this->moreReplaceNamespace->each(
                function ($item, $key) use (&$searches, &$replacements) {
                    $item = trim($item);
                    $key = trim($key);

                    if ($item && $key) {
                        $searches[0][] = Str::studly($key);
                        $searches[1][] = '{{ ' . Str::camel($key) . ' }}';
                        $searches[2][] = '{{' . Str::camel($key) . '}}';
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
     * @param  string $classNamespace
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

    /**
     * @return bool
     */
    protected function shouldCreateDomain(): bool
    {
        return $this->hasOption('force-domain') && $this->option('force-domain');
    }

    /*****
     * ELOQUENT MODEL RELATED
     *****/

    /**
     * @param  string $option
     * @return string
     */
    protected function getModelClass(string $option): string
    {
        $modelClass = $this->parseModel($this->option($option));

        if (! class_exists($modelClass)) {
            if ($this->confirm("$modelClass model does not exist. Do you want to generate it?", true)) {
                $args = $this->getPackageArgs();
                $args['name'] = $modelClass;

                $this->call('gen:model', $args);
            } else {
                $alternativeModels = collect();

                if (($packageDomainFullPath = $this->getPackageDomainFullPath()) !== app_path()) {
                    if (file_exists($temp = $packageDomainFullPath . '/Models')) {
                        $alternativeModels = $alternativeModels->merge(collect_classes_from_path($temp)?->values());
                    }

                    if ($this->package_dir &&
                        ($temp = package_app_path($this->package_dir)) &&
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
                    'Choose alternative ' . ($option === 'parent' ? $option . ' ' : null) . 'model',
                    $alternativeModels->prepend($defaultAlternativeModel)->toArray(),
                    0
                );

                $modelClass = $modelClass === $defaultAlternativeModel ? null : $modelClass;

                $this->input->setOption($option, $modelClass);
            }
        }

        return $modelClass;
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string $model
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

    /*****
     * TEST RELATED
     *****/

    /**
     * Create the matching test case if requested.
     *
     * @param  string $path
     * @return void
     */
    protected function handleTestCreation($path): void
    {
        $appPath = $this->package_dir ? package_app_path($this->package_dir) : $this->laravel['path'];

        $args = $this->getPackageArgs();
        $args['name'] = Str::of($path)->after($appPath)->beforeLast('.php')->append('Test')->replace('\\', '/');
        $args['--pest'] = $this->option('pest');
        $args['--no-interaction'] = true;

        $this->call('gen:test', $args);
    }
}
