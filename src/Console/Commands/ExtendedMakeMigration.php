<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

/**
 * Class ExtendedMakeResource
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ExtendedMakeMigration extends MigrateMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'gen:migration
        {name : The name of the migration}
        {vendor? : Vendor name}
        {package? : Package name}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class for Eloquent model.';

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
    public function handle()
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
}
