<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesEloquentModel;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use JetBrains\PhpStorm\Pure;

/**
 * Class TraitMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class TraitMakeCommand extends GeneratorCommand
{
    use UsesEloquentModel;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Trait';

    /***** OVERRIDDEN FUNCTIONS *****/

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addModelOptions();
    }

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        $this->setModelFields();

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/trait' . ($this->option('model') ? '.factory' : '') . '.custom.stub';
    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions()
        );
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '/Traits';
    }
}
