<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\ExtendedMigrationCreator;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;

/**
 * Class ExtendedMakeMigration
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ExtendedMakeMigration extends MigrateMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'gen:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--package= : Target package to generate the files (e.g., `vendor-name/package-name`).}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file using custom stub.';

    /***** OVERRIDDEN FUNCTIONS *****/

    public function __construct()
    {
        $realPath = dirname(__DIR__, 3) . '/stubs';

        $creator = new ExtendedMigrationCreator(app('files'), $realPath);

        parent::__construct($creator, app('composer'));
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        // Set MigrationCreator $package_path
        if ($this->package_path && $this->creator instanceof ExtendedMigrationCreator) {
            $this->creator->setPackagePath($this->package_path);
        }

        parent::handle();
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
