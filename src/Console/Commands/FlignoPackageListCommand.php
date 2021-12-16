<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;

/**
 * Class FlignoPackageListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-12-06
 */
class FlignoPackageListCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all locally installed packages.';

    /**
     * Execute the console command.
     * @throws \JsonException
     */
    public function handle(): void
    {
        $this->table(
            [
                'Package',
                'Status'
            ],
            $this->getPackagesRows()
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
