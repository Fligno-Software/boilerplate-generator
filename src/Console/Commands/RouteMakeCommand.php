<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RouteMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-25
 */
class RouteMakeCommand extends GeneratorCommand
{
    use UsesVendorPackageDomainTrait;

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
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions(true, true);
    }

    /**
     * @throws FileNotFoundException
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): bool|int|null
    {
        $this->setVendorAndPackage();

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

        $ctr = 0;

        foreach ($routes as $name) {
            $path = $this->getPath($name);

            if (! $this->shouldOverwrite() && file_exists($path)) {
                $ctr++;
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

        return $ctr ? self::FAILURE : self::SUCCESS;
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
        if (file_exists($temp = __DIR__ . '/../../../stubs/' . $route . '.custom.stub')) {
            return $temp;
        }

        return __DIR__ . '/../../../stubs/api.custom.stub';
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        if ($this->domain_dir) {
            return ($this->package_dir ? package_app_path($this->package_dir) : app_path()) . '/' . $this->domain_dir . '/routes';
        }

        return $this->package_dir ? package_routes_path($this->package_dir) : base_path('routes');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['api', null, InputOption::VALUE_NONE, 'Generate api route.'],
            ['web', null, InputOption::VALUE_NONE, 'Generate web route.']
        ];
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return null;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return '';
    }
}
