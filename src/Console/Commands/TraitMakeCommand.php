<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class TraitMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class TraitMakeCommand extends GeneratorCommand
{
    use UsesVendorPackageTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Trait';

    /**
     * @var string|null
     */
    protected ?string $factory_class = null;

    /**
     * @var string|null
     */
    protected ?string $factory_name = null;

    /**
     * @var bool
     */
    protected bool $factory_exists = false;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();

        $this->addFactoryOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
       $this->setVendorAndPackage();

       $this->setFactoryFields();

       return parent::handle();
    }

    /**
     * @return void
     */
    protected function addFactoryOptions(): void
    {
        $this->getDefinition()->addOption(new InputOption(
            'factory', 'f', InputOption::VALUE_REQUIRED, 'Factory to be included.'
        ));
    }

    /**
     * @return void
     */
    protected function setFactoryFields(): void
    {
        if(
            ($factory = $this->option('factory')) && (
                $this->checkFactoryExists($factory, false) ||
                $this->checkFactoryExists($factory, true, true)||
                $this->checkFactoryExists($factory)
            )
        ) {
            $this->addMoreCasedReplaceNamespace($factory, 'Factory');
        }
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/trait' . ($this->option('factory') ? '.factory' : '') . '.custom.stub';
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '/Traits';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Trait';
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @param string|null $factory_class
     */
    public function setFactoryClass(?string $factory_class): void
    {
        $this->factory_class = $factory_class;

        $this->addMoreReplaceNamespace([
            'FactoryClass' => $this->factory_class
        ]);
    }

    /**
     * @param string|null $factory_name
     */
    public function setFactoryName(?string $factory_name): void
    {
        $this->factory_name = Str::of($factory_name)->afterLast('\\');

        $this->addMoreReplaceNamespace([
            'FactoryName' => $this->factory_name
        ]);
    }

    /**
     * @param string $factory
     * @param bool $qualifyFactoryClass
     * @param bool $disablePackageNamespaceTemporarily
     * @return bool
     */
    protected function checkFactoryExists(string &$factory, bool $qualifyFactoryClass = true, bool $disablePackageNamespaceTemporarily = false): bool
    {
        if ($disablePackageNamespaceTemporarily) {
            $this->is_package_namespace_disabled = true;
        }

        if ($qualifyFactoryClass) {
            $factory = $this->qualifyFactoryClass($factory);
        }
        else {
            $factory = (string) $this->cleanClassNamespace($factory);
        }

        $this->is_package_namespace_disabled = false;

        $this->factory_exists = class_exists($factory);

        return $this->factory_exists;
    }

    /**
     * @return string
     */
    protected function getRootNamespaceDuringReplaceNamespace(): string
    {
        $rootNameSpace = $this->rootNamespace();

        if ($rootNameSpace !== $this->package_namespace) {
            $rootNameSpace = '';
        }

        return $rootNameSpace;
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     * @return string
     */
    protected function qualifyFactoryClass(string $name): string
    {
        $name = (string) $this->cleanClassNamespace($name);

        $rootNamespace = $this->rootFactoryNamespace();

        if (!$rootNamespace || Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyFactoryClass(trim($rootNamespace, '\\').'\\'.$name);
    }

    /**
     * @return string
     */
    protected function rootFactoryNamespace(): string
    {
        $default = 'Database\\Factories';
        if ($this->is_package_namespace_disabled || ! $this->package_namespace) {
            return $default;
        }

        return $this->package_namespace;
    }
}
