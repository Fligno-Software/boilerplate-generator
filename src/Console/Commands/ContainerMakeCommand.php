<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ContainerMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2022-01-13
 */
class ContainerMakeCommand extends GeneratorCommand
{
    use UsesVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:container';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service container in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service Container';

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

        $res = parent::handle();

        $this->createContainerHelper();

        $this->createContainerFacade();

        return $res;
    }

    /**
     * @return void
     * @throws MissingNameArgumentException
     */
    protected function createContainerHelper(): void
    {
        $this->call('gen:helper', array_merge(
            $this->getPackageArgs(),
            [
                'name' => $this->getNameInput(),
                '--container' => $this->getNameInput()
            ]
        ));
    }

    /**
     * @return void
     * @throws MissingNameArgumentException
     */
    protected function createContainerFacade(): void
    {
        $this->call('gen:facade', array_merge(
            $this->getPackageArgs(),
            [
                'name' => $this->getNameInput(),
                '--container' => $this->getNameInput()
            ]
        ));
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/container.custom.stub';
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '/Containers';
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
