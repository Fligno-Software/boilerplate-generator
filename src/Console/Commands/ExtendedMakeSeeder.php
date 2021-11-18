<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeSeeder
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-15
 */
class ExtendedMakeSeeder extends SeederMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class using custom stub.';

    /***** OVERRIDDEN FUNCTIONS *****/

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
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/seeder.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions()
        );
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $path = $this->package_dir ? package_database_path($this->package_dir)  : $this->laravel->databasePath();

        if (is_dir($path.'/seeds')) {
            return $path.'/seeds/'.$name.'.php';
        }

        return $path.'/seeders/'.$name.'.php';
    }
}
