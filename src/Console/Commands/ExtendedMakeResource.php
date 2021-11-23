<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Support\Facades\File;

/**
 * Class ExtendedMakeResource
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeResource extends ResourceMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource file in Laravel or in a specific package.';

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
        $stub = '/../../../stubs/resource.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
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
            $this->getDefaultPackageOptions()
        );
    }
}
