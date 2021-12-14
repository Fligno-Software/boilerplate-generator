<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesEloquentModel;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use JsonException;

/**
 * Class DddActionMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class DddActionMakeCommand extends GeneratorCommand
{
    use UsesEloquentModel;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'gen:ddd:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DDD action class in Laravel or in a specific package.';

    protected $type = 'DDD Action';

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
        $this->addModelOptions();
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     * @throws PackageNotFoundException|JsonException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        $this->setModelFields();

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/controller.ddd.custom.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceRequestNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Overriding to inject more namespace.
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceRequestNamespace(string &$stub, string $name): static
    {
        $action = Str::of($name)->afterLast('\\')->before('Controller');

        // Generate Request
        $requestClass = $action . 'Request';
        $requestClassPath = ($this->model_exists ? $this->model_class . '\\' : '') . $requestClass;
        $namespacedRequestClass = $this->rootNamespace() . 'Http\\Requests\\'. $requestClassPath;

        $requestArgs = $this->getPackageArgs();
        $requestArgs['name'] = $requestClassPath;
        $this->call('gen:request', $requestArgs);

        $searches = [
            ['{{ actionRequest }}', '{{ namespacedActionRequest }}'],
            ['{{actionRequest}}', '{{namespacedActionRequest}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$requestClass, $namespacedRequestClass],
                $stub
            );
        }

        return $this;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->getValidatedNameInput('Controller');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Controllers';
    }
}