<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\PolicyMakeCommand;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeListener
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakePolicy extends PolicyMakeCommand
{
    use UsesVendorPackageInput;

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
        $policyStub = __DIR__ . '/../../../stubs/policy.custom.stub';
        $policyPlainStub = __DIR__ . '/../../../stubs/policy.plain.custom.stub';

        if (
            file_exists($policyStub) === FALSE ||
            file_exists($policyPlainStub) === FALSE
        ) {
            return parent::getStub();
        }

        return $this->option('model')
            ? $policyStub
            : $policyPlainStub;
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
