<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ServiceMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2022-01-13
 */
class ServiceMakeCommand extends GeneratorCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:make:service';

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
    protected $type = 'Service';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions();
    }

    /*****
     * OVERRIDDEN FUNCTIONS
     *****/

    /**
     * @return bool|null
     *
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

        $res = parent::handle();

        $this->createContainerHelper();

        $this->createContainerFacade();

        return $res && starterKit()->clearCache();
    }

    /**
     * @return void
     *
     * @throws MissingNameArgumentException
     */
    protected function createContainerHelper(): void
    {
        $this->call(
            'bg:make:helper',
            array_merge(
                $this->getPackageArgs(),
                [
                    'name' => $this->getNameInput(),
                    '--service' => $this->getNameInput(),
                ]
            )
        );
    }

    /**
     * @return void
     *
     * @throws MissingNameArgumentException
     */
    protected function createContainerFacade(): void
    {
        $this->call(
            'bg:make:facade',
            array_merge(
                $this->getPackageArgs(),
                [
                    'name' => $this->getNameInput(),
                    '--service' => $this->getNameInput(),
                ]
            )
        );
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../../stubs/service/service.custom.stub';
    }

    /**
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'/Services';
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
