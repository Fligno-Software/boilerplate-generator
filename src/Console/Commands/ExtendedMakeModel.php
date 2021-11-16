<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeModel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeModel extends ModelMakeCommand
{
    use UsesVendorPackageInput;

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
    protected $description = 'Create a new model using custom stub.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        return parent::handle();
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

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
//            '--package' => $this->package_path
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory(): void
    {
        $this->info('Passing at gen:factory');
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
        $this->info('Passing at gen:seeder');
        $seeder = Str::studly(class_basename($this->argument('name')));

        $args = $this->getInitialArgs();
        $args['name'] = "{$seeder}Seeder";

        $this->call('gen:seeder', $args);
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
     * @return array|array[]
     */
    protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->default_package_options
        );
    }
}
