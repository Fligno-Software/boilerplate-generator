<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesEloquentModel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeFactory
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-15
 */
class ExtendedMakeFactory extends FactoryMakeCommand
{
    use UsesEloquentModel;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory in Laravel or in a specific package.';

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();

        $this->addModelOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        $this->setModelFields();

        $res = parent::handle();

        $this->createFactoryTrait();

        return $res;
    }

    /**
     * return void
     * @throws MissingNameArgumentException
     */
    protected function createFactoryTrait(): void
    {
        if ($this->package_name && $this->model_name)
        {
            $this->call('gen:trait', array_merge(
                $this->getPackageArgs(),
                [
                    'name' => 'Has' . $this->getNameInput(),
                    '--factory' => $this->getNameInput()
                ]
            ));
        }
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/factory.custom.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = (string) Str::of($name)->replaceFirst($this->rootNamespace(), '')->finish('Factory');

        $path = $this->package_dir ? package_database_path($this->package_dir)  : $this->laravel->databasePath();

        return $path.'/factories/'.str_replace('\\', '/', $name).'.php';
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
}
