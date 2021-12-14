<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use JsonException;

/**
 * Class FlignoPackageRemoveCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-12-06
 */
class FlignoPackageRemoveCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:remove';

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
    public function __construct()
    {
        parent::__construct();

        $this->addPackageArguments();
    }

    /**
     * Execute the console command.
     * @throws PackageNotFoundException|JsonException
     */
    public function handle(): void
    {
        $this->setVendorAndPackage();

        $this->call('packager:remove', [
            'vendor' => $this->vendor_name,
            'name' => $this->package_name
        ]);
    }
}
