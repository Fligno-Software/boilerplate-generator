<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ProviderMakeCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ProviderDisableCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class ProviderDisableCommand extends ProviderMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:provider:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a service provider in Laravel or in a specific package.';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions();

        $this->getDefinition()->addOption(
            new InputOption(
                '--starter-kit',
                null,
                InputOption::VALUE_NONE,
                'Extend the BaseStarterKitServiceProvider.'
            )
        );
    }

    /*****
     * OVERRIDDEN FUNCTIONS
     *****/

    /**
     * @return bool|null
     *
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();


        // for packages and domains
        if ($this->package_dir) {

        }
        $handled = parent::handle();

//        starterKit()->clearCache()

        return self::SUCCESS;
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('starter-kit')) {
            return __DIR__.'/../../../stubs/provider/provider.sk.custom.stub';
        }

        return __DIR__.'/../../../stubs/provider/provider.custom.stub';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Provider';
    }
}
