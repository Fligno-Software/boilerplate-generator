<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ClassMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ClassMakeCommand extends GeneratorCommand
{
    use UsesVendorPackage;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new class in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();

        if ($this->getDefinition()->hasOption('abstract') === false) {
            $this->getDefinition()->addOption(new InputOption(
                'abstract',
                'a',
                InputOption::VALUE_NONE,
                'Set class as abstract.'
            ));
        }
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
       $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/class' . ($this->option('abstract') ? '.abstract' : '') .'.custom.stub';
    }
}
