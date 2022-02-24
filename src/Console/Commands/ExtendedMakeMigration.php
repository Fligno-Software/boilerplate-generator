<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\ExtendedMigrationCreator;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

/**
 * Class ExtendedMakeMigration
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ExtendedMakeMigration extends MigrateMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'gen:migration {name : The name of the migration}
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
    protected $description = 'Create a new migration file in Laravel or in a specific package.';

    public function __construct()
    {
        $realPath = dirname(__DIR__, 3) . '/stubs';

        $creator = new ExtendedMigrationCreator(app('files'), $realPath);

        parent::__construct($creator, app('composer'));

        $this->addPackageOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return void
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain();

        if ($this->creator instanceof ExtendedMigrationCreator) {
            tap($this->creator)->setPackageDir($this->package_dir)->setDomainDir($this->domain_dir);
        }

        parent::handle();
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
