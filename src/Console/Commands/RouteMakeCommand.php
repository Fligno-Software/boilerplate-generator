<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RouteMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2021-11-25
 */
class RouteMakeCommand extends GeneratorCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new route file in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /*****
     * OVERRIDDEN FUNCTIONS
     *****/

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions(true, true);
    }

    /**
     * @throws PackageNotFoundException|MissingNameArgumentException|FileNotFoundException
     */
    public function handle(): bool|int|null
    {
        $this->setVendorPackageDomain();

        $name = $this->getNameInput();

        $path = $this->getPath($name);

        if (! $this->shouldOverwrite() && file_exists($path)) {
            $this->error(Str::ucfirst($name) . ' route already exists!');
            return self::FAILURE;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type.' created successfully.');

        return starterKit()->clearCache();
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['api', null, InputOption::VALUE_NONE, 'Generate api route.']
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/route.custom.stub';
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
     * Get the validated desired class name from the input.
     *
     * @return string
     */
    protected function getValidatedNameInput(): string
    {
        return Str::of(preg_replace('/[^A-Za-z\d]/', ' ', trim($this->argument('name'))))
            ->snake('-')
            ->replace('api', '')
            ->trim('-')
            ->when($this->option('api'), fn(Stringable $str) => $str->append('.api'))
            ->trim('.');
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        if ($this->domain_dir) {
            return ($this->package_dir ? package_app_path($this->package_dir) :
                    app_path()) . '/' . $this->domain_dir . '/routes';
        }

        return $this->package_dir ? package_routes_path($this->package_dir) : base_path('routes');
    }
}
