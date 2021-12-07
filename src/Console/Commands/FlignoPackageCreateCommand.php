<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FlignoPackageCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class FlignoPackageCreateCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel package.';

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
     */
    public function handle(): void
    {
        $this->setVendorAndPackage($this);

        $initiate_boilerplate = TRUE;

        try {
            $this->call('packager:new', [
                'vendor' => $this->vendor_name,
                'name' => $this->package_name,
                '--i' => true
            ]);
        }
        catch (\Exception $exception) {
            $initiate_boilerplate = FALSE;
        }

        if ($initiate_boilerplate) {
            $args = $this->getPackageArgs();

            $args['model'] = $this->package_name_studly;
            $args['--yes'] = $this->option('yes');

            $this->call('fligno:start', $args);

            $this->call('gen:routes', [
                'vendor' => $this->vendor_name,
                'package' => $this->package_name,
            ]);
        }
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            [
                ['yes', 'y', InputOption::VALUE_NONE, 'Yes to all generate questions.'],
            ]
        );
    }
}
