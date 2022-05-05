<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ExtendedMakeModel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2021-11-09
 */
class ExtendedMakeModel extends ModelMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class in Laravel or in a specific package.';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
    }

    /*****
     * OVERRIDDEN FUNCTIONS
     *****/

    /**
     * @return void
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain();

        if ($this->option('all')) {
            $this->input->setOption('repository', true);
            $this->input->setOption('observer', true);
            $this->input->setOption('df', true);
        }

        parent::handle();

        if ($this->option('repository')) {
            $this->createRepository();
        }

        if ($this->option('observer')) {
            $this->createObserver();
        }

        if ($this->option('df')) {
            $this->createDataFactory();
        }

        starterKit()->clearCache();
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration(): void
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $args = $this->getPackageArgs();
        $args['name'] = "create_{$table}_table";
        $args['--create'] = $table;
        $args['--no-interaction'] = true;

        $this->call('gen:migration', $args);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory(): void
    {
        $name = $this->argument('name');

        $factory = Str::studly($name);

        $args = $this->getPackageArgs();
        $args['name'] = $factory;
        $args['--model'] = $this->qualifyClass($name);
        $args['--no-interaction'] = true;

        $this->call('gen:factory', $args);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder(): void
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $args = $this->getPackageArgs();
        $args['name'] = $seeder;
        $args['--no-interaction'] = true;

        $this->call('gen:seeder', $args);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController(): void
    {
        $name = $this->argument('name');

        $controller = Str::studly(class_basename($name));

        $modelName = $this->qualifyClass($name);

        $args = $this->getPackageArgs();
        $args['name'] = $controller;
        $args['--model'] = $this->option('resource') || $this->option('api') ? $modelName : null;
        $args['--api'] = $this->option('api');
        $args['--skip-model'] = true;
        $args['--no-interaction'] = true;

        $this->call('gen:controller', array_filter($args));
    }

    /**
     * Create a repository file for the model.
     *
     * @return void
     */
    protected function createRepository(): void
    {
        $name = $this->argument('name');

        $repository = Str::studly(class_basename($name));

        $args = $this->getPackageArgs();
        $args['name'] = $repository;
        $args['--no-interaction'] = true;

        $this->call('gen:repository', $args);
    }

    /**
     * Create an observer file for the model.
     *
     * @return void
     */
    protected function createObserver(): void
    {
        $repository = Str::studly(class_basename($this->argument('name')));

        $args = $this->getPackageArgs();
        $args['name'] = $repository;
        $args['--model'] = $this->qualifyClass($repository);
        $args['--no-interaction'] = true;

        $this->call('gen:observer', $args);
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy(): void
    {
        $name = $this->argument('name');

        $policy = Str::studly(class_basename($name));

        $args = $this->getPackageArgs();
        $args['name'] = $policy;
        $args['--model'] = $this->qualifyClass($name);
        $args['--no-interaction'] = true;

        $this->call('gen:policy', $args);
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createDataFactory(): void
    {
        $dataFactory = Str::studly(class_basename($this->argument('name')));

        $args = $this->getPackageArgs();
        $args['name'] = $dataFactory;
        $args['--model'] = $this->qualifyClass($dataFactory);
        $args['--no-interaction'] = true;

        $this->call('gen:df', $args);
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/model.custom.stub';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            [
                ['repository', null, InputOption::VALUE_NONE, 'Create new repository class based on the model.'],
                ['observer', 'o', InputOption::VALUE_NONE, 'Create new observer class based on the model.'],
                ['df', null, InputOption::VALUE_NONE, 'Create new data factory class based on the model.'],
            ]
        );
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
}
