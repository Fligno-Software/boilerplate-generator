<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesCommandMultipleTargetsTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesCommandMultipleTargetsTrait
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * @var array|null
     */
    protected array|null $targets = null;

    /**
     * @return void
     */
    public function addMultipleTargetsOption(): void
    {
        $this->getDefinition()->addOptions([
            new InputOption('all', 'a', InputOption::VALUE_NONE, 'Run all Laravel tests and tests within packages.'),
            new InputOption('packages', 'p', InputOption::VALUE_NONE, 'Run all tests within packages.'),
            new InputOption('target', null, InputOption::VALUE_OPTIONAL, 'Root and/or package/s (e.g., `vendor-name/package-name`).'),
        ]);
    }
}
