<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesForceFileCreateTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesOverwriteFileTrait
{
    /**
     * @return void
     */
    protected function addOverwriteFileOptions(): void
    {
        if ($this->getDefinition()->hasOption('force') === FALSE) {
            $this->getDefinition()->addOption(new InputOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite file if exists.'));
        }
    }

    /**
     * @return bool
     */
    protected function shouldOverwrite(): bool
    {
        return (bool) $this->option('force');
    }
}
