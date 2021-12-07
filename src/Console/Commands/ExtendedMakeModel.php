<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ExtendedMakeModel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeModel extends ModelMakeCommand
{
    use UsesCreatesMatchingTest;

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
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    public function handle()
    {
        $this->setVendorAndPackage($this);

        parent::handle();

        if ($this->option('all')) {
            $this->input->setOption('repo', true);
        }

        if ($this->option('repo')) {
            $this->createRepository();
        }
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

        $this->call('gen:migration', $args);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory(): void
    {
        $factory = Str::studly($this->argument('name'));

        $args = $this->getPackageArgs();
        $args['name'] = "{$factory}Factory";
        $args['--model'] = $this->qualifyClass($this->getNameInput());

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
        $args['name'] = "{$seeder}Seeder";

        $this->call('gen:seeder', $args);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController(): void
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $args = $this->getPackageArgs();
        $args['name'] = "{$controller}Controller";
        $args['--model'] = $this->option('resource') || $this->option('api') ? $modelName : null;
        $args['--api'] = $this->option('api');
        $args['--requests'] = $this->option('requests') || $this->option('all');
        $args['--skip-model'] = true;

        $this->call('gen:controller', array_filter($args));
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createRepository(): void
    {
        $repository = Str::studly(class_basename($this->argument('name')));

        $args = $this->getPackageArgs();
        $args['name'] = "{$repository}Repository";
        $args['--model'] = $this->qualifyClass($this->getNameInput());

        $this->call('gen:repository', $args);
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy(): void
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $args = $this->getPackageArgs();
        $args['name'] = "{$policy}Policy";
        $args['--model'] = $this->qualifyClass($this->getNameInput());

        $this->call('gen:policy', $args);
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/model.custom.stub';

        if (file_exists($path = __DIR__ . $stub) === false) {
            return parent::getStub();
        }

        return $path;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            [
                ['repo', null, InputOption::VALUE_NONE, 'Create new repository class based on the model.'],
            ]
        );
    }
}
