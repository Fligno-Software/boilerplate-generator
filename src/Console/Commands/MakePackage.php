<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MagicStarter
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class MakePackage extends Command
{
    use UsesVendorPackageInput;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'gen:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Laravel package. [Wrapper for Jeroen-G/laravel-packager]';

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
            $args = $this->getInitialArgs();

            $args['model'] = $this->package_name_studly;

            $this->call('gen:start', $args);
        }
    }

    /***** GETTERS & SETTERS *****/


    /***** OTHER METHODS *****/


    /***** MODEL VALIDATION *****/

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['vendor', InputArgument::REQUIRED, 'The name of the vendor.'],
            ['package', InputArgument::REQUIRED, 'The name of the package to create.'],
        ];
    }

    /**
     * @return array|array[]
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
