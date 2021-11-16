<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

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
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'gen:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file using custom stub.';

    /***** OVERRIDDEN FUNCTIONS *****/

    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct(app('migration.creator'), app('composer'));
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        // Initiate Stuff

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
