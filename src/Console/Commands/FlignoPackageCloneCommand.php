<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FlignoPackageCloneCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-12-06
 */
class FlignoPackageCloneCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:clone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone a Laravel package using Git.';

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
            'url', InputArgument::REQUIRED, 'The Git URL of package to clone.'
        ));
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->setVendorAndPackage($this);

        $this->call('packager:git', [
            'vendor' => $this->vendor_name,
            'name' => $this->package_name,
            'url' => $this->argument('url')
        ]);
    }
}