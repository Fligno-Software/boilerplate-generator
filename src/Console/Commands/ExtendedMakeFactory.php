<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandEloquentModelTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeFactory
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-15
 */
class ExtendedMakeFactory extends FactoryMakeCommand
{
    use UsesCommandEloquentModelTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:make:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory in Laravel or in a specific package.';

    /**
     * @param  Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions();

        $this->addModelOptions();
    }

    /*****  OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     *
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

        $this->setModelFields();

        $res = parent::handle();

        $this->createFactoryTrait();

        return $res && (starterKit()->clearCache() ? self::SUCCESS : self::FAILURE);
    }

    /**
     * return void
     *
     * @throws MissingNameArgumentException
     */
    protected function createFactoryTrait(): void
    {
        if (($this->package_name || $this->domain_name) && $this->model_name) {
            $this->call(
                'bg:make:trait',
                array_merge(
                    $this->getPackageArgs(),
                    [
                        'name' => 'Has'.$this->getNameInput(),
                        '--factory' => $this->getNameInput(),
                    ]
                )
            );
        }
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/factory/factory.custom.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::of($name)
            ->replaceFirst($this->rootNamespace(), '')
            ->after('Database\\Factories\\')
            ->replace('\\', '/')
            ->finish('Factory')
            ->jsonSerialize();

        $path = $this->getPackageDomainFullPath();

        return $path.'/factories/'.$name.'.php';
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Database\\Factories';
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
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Factory';
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        if ($this->domain_dir) {
            return ($this->package_dir ? package_app_path($this->package_dir).'/'.$this->domain_dir :
                    app_path($this->domain_dir)).'/database';
        }

        return $this->package_dir ? package_database_path($this->package_dir) : database_path();
    }
}
