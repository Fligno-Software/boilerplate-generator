<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

/**
 * Class PackageRemoveCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-12-06
 */
class PackageRemoveCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bg:package:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a Laravel package.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct(protected Composer $composer)
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
                'packager:remove',
                [
                    'vendor' => $this->vendor_name,
                    'name' => $this->package_name,
                    '--no-interaction' => true,
                ]
            );
        }

        // Clear starter kit cache and run composer dump
        starterKit()->clearCache();
        $this->composer->dumpAutoloads();
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
