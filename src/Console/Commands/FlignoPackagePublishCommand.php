<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use JsonException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FlignoPackagePublishCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-12-06
 */
class FlignoPackagePublishCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a Laravel package using Git.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageArguments();

        $this->getDefinition()->addArgument(new InputArgument(
            'url', InputArgument::REQUIRED, 'The Git URL where the package should be published.'
        ));
    }

    /**
     * Execute the console command.
     * @throws PackageNotFoundException|JsonException
     */
    public function handle(): void
    {
        $this->setVendorAndPackage();

        $this->call('packager:publish', [
            'vendor' => $this->vendor_name,
            'name' => $this->package_name,
            'url' => $this->argument('url')
        ]);
    }
}
