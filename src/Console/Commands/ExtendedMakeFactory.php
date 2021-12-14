<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesEloquentModel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JsonException;

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
     * @throws FileNotFoundException|PackageNotFoundException|JsonException
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
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->getValidatedNameInput('Factory');
    }

    /**
     * return void
     */
    protected function createFactoryTrait(): void
    {
        if ($this->package_name && $this->model_name)
        {
            $this->call('gen:trait', array_merge(
                $this->getEloquentModelArgs(),
                $this->getPackageArgs(),
                [
                    'name' => 'Has' . $this->model_name . 'Factory'
                ]
            ));
        }
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/factory.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
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
}
