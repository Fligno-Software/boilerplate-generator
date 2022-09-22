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
     * @param  string  $option_name
     * @return void
     */
    public function addMultipleTargetsOption(string $option_name = 'package'): void
    {
        $this->package_option_argument_name = $option_name;

        $this->getDefinition()->addOptions([
            new InputOption('all', 'a', InputOption::VALUE_NONE, 'Apply to Laravel and packages.'),
            new InputOption('packages', 'p', InputOption::VALUE_NONE, 'Apply to packages only.'),
            new InputOption($this->package_option_argument_name, null, InputOption::VALUE_OPTIONAL, 'Apply to  (e.g., `vendor-name/package-name`).'),
        ]);
    }
}
