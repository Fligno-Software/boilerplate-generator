<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RouteMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-25
 */
class RouteMakeCommand extends GeneratorCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create web and api route files in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        $routes = [];

        if ($this->option('web') === FALSE && $this->option('api') === FALSE) {
            $this->input->setOption('web', true);
            $this->input->setOption('api', true);
        }

        if ($this->option('web')) {
            $routes[] = 'web';
        }

        if ($this->option('api')) {
            $routes[] = 'api';
        }

        foreach ($routes as $name) {
            $path = $this->getPath($name);

            if (file_exists($path)) {
                $this->error(Str::ucfirst($name) . ' route already exists!');
                continue;
            }

            // Next, we will generate the path to the location where this class' file should get
            // written. Then, we will build the class and make the proper replacements on the
            // stub files so that it gets the correctly formatted namespace and class name.
            $this->makeDirectory($path);

            $this->files->put($path, $this->sortImports($this->buildClass($name)));

            $this->info($this->type.' created successfully.');
        }

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
        $stub = $this->files->get($this->getRouteStub($name));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param string $route
     * @return string
     */
    protected function getRouteStub(string $route): string
    {
        $defaultStub = __DIR__ . '/../../../stubs/api.custom.stub';

        if (file_exists($temp = __DIR__ . '/../../../stubs/' . $route . '.custom.stub')) {
            return $temp;
        }

        return $defaultStub;
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

        $path = $this->package_dir ? package_routes_path($this->package_dir).DIRECTORY_SEPARATOR : base_path('routes');

        return $path.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['vendor', InputArgument::REQUIRED, 'The name of the vendor.'],
            ['package', InputArgument::REQUIRED, 'The name of the target package.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['api', null, InputOption::VALUE_NONE, 'Generate api route.'],
            ['web', null, InputOption::VALUE_NONE, 'Generate web route.'],
        ];
    }


    protected function getStub()
    {
        return null;
    }
}