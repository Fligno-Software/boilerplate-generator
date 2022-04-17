<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FlignoPackageCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2021-11-09
 */
class FlignoPackageCreateCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

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
     *
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain(false, false);

        $this->call(
            'packager:new',
            [
                'vendor' => $this->vendor_name,
                'name' => $this->package_name,
                '--i' => true
            ]
        );

        if (! $this->isNoInteraction()) {
            collect(['web', 'api'])->each(
                function ($value) {
                    $this->call(
                        'gen:route',
                        [
                            'name' => $value,
                            '--package' => $this->package_dir,
                            '--api' => $value !== 'web'
                        ]
                    );
                }
            );

            $this->call(
                'gen:gitlab',
                [
                    '--package' => $this->package_dir
                ]
            );

            $this->call(
                'gen:helper',
                [
                    'name' => $this->package_name,
                    '--package' => $this->package_dir
                ]
            );
        }

        starterKit()->clearCache();
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
