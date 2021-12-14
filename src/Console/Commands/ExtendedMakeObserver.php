<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use JsonException;

/**
 * Class ExtendedMakeListener
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeObserver extends ObserverMakeCommand
{
    use UsesVendorPackage;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer class in Laravel or in a specific package.';

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
     * @throws FileNotFoundException|PackageNotFoundException|JsonException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        return parent::handle();
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->getValidatedNameInput('Observer');
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $observerStub = __DIR__ . '/../../../stubs/observer.custom.stub';
        $observerPlainStub = __DIR__ . '/../../../stubs/observer.plain.custom.stub';

        if (
            file_exists($observerStub) === FALSE ||
            file_exists($observerPlainStub) === FALSE
        ) {
            return parent::getStub();
        }

        return $this->option('model')
            ? $observerStub
            : $observerPlainStub;
    }
}
