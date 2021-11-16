<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeFactory
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-15
 */
class ExtendedMakeFactory extends FactoryMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new factory class using custom stub.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->info('Passed at gen:factory @ ' . $this->rootNamespace());

        $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/factory.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
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

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = (string) Str::of($name)->replaceFirst($this->rootNamespace(), '')->finish('Factory');

        $path = $this->package_path ? package_database_path($this->package_path)  : $this->laravel->databasePath();

        return $path.'/factories/'.str_replace('\\', '/', $name).'.php';
    }
}
