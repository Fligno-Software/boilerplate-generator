<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeRequest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeRequest extends RequestMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request with authorize as true.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->info('Creating requests for ' . $this->vendor_name . '/' . $this->package_name . '...');

        $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/request.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === false) {
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
