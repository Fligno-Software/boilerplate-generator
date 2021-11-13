<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
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
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'gen:migration';

    /***** OVERRIDDEN FUNCTIONS *****/

    public function __construct()
    {
        $customStubPath = __DIR__ . '/../../../stubs/migration.create.custom.stub';

        $files = new Filesystem();
        $creator = new MigrationCreator($files , $customStubPath);
        $composer = new Composer($files);
        parent::__construct($creator, $composer);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        // Initiate Stuff

        $this->info('Creating migration for ' . $this->vendor_name . '/' . $this->package_name . '...');

        $this->setVendorAndPackage($this);

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

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        return $this->package_path ? package_migration_path($this->package_path)  : parent::getMigrationPath();
    }
}
