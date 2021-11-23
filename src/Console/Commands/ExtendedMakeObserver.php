<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeListener
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeObserver extends ObserverMakeCommand
{
    use UsesVendorPackageInput;

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

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        return parent::handle();
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

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions(false)
        );
    }
}
