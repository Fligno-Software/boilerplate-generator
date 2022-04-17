<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;

/**
 * Class FlignoPackageDisableCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2021-12-06
 */
class FlignoPackageDisableCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a Laravel package.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageArguments(false);
    }

    /**
     * Execute the console command.
     *
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain(true, false, false);

        if ($this->vendor_name && $this->package_name) {
            $this->call(
                'packager:disable',
                [
                    'vendor' => $this->vendor_name,
                    'name' => $this->package_name
                ]
            );
        }

        starterKit()->clearCache();
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return null;
    }
}
