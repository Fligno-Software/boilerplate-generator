<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RepositoryMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class RepositoryMakeCommand extends GeneratorCommand
{
    use UsesVendorPackageTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/repository.custom.stub';
    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            [
                ['model', null, InputOption::VALUE_REQUIRED, 'Eloquent Model to base the repository class from.']
            ]
        );
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '/Repositories';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Repository';
    }
}
