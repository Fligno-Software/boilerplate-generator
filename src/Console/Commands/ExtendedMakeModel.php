<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
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

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return false|void
     */
    public function handle()
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('policy', true);
            $this->input->setOption('resource', true);
            $this->input->setOption('repo', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createCustomMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->createController();
        }

        if ($this->option('repo')) {
            $this->createRepository();
        }

        if ($this->option('policy')) {
            $this->createPolicy();
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

        $this->call('gen:migration', [
            'name' => "create_{$table}_table",
            '--package' => $this->package_dir
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory(): void
    {
        $factory = Str::studly($this->argument('name'));

        $args = $this->getInitialArgs();
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

        $args = $this->getInitialArgs();
        $args['name'] = "{$seeder}Seeder";

        $this->call('gen:seeder', $args);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createCustomMigration(): void
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $args = $this->getInitialArgs();
        $args['name'] = "create_{$table}_table";
        $args['--create'] = $table;

        $this->call('gen:migration', $args);
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

        $this->call('gen:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
        ]));
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createRepository(): void
    {
        $repository = Str::studly(class_basename($this->argument('name')));

        $args = $this->getInitialArgs();
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

        $args = $this->getInitialArgs();
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
            $this->getDefaultPackageOptions(),
            [
                ['repo', null, InputOption::VALUE_NONE, 'Create new repository class based on the model.'],
            ]
        );
    }
}
