<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ExtendedMakeListener
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakePolicy extends PolicyMakeCommand
{
    use UsesVendorPackageTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class in Laravel or in a specific package.';

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('model')
            ? __DIR__ . '/../../../stubs/policy.custom.stub'
            : __DIR__ . '/../../../stubs/policy.plain.custom.stub';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Policy';
    }
}
