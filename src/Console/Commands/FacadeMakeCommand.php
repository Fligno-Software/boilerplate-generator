<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandServiceTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class FacadeMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2022-01-17
 */
class FacadeMakeCommand extends GeneratorCommand
{
    use UsesCommandServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:make:facade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new facade in Laravel or in a specific package.';

    /**
     * @var string
     */
    protected $type = 'Facade';

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

        $this->addServiceOptions();
    }

    /**
     * @return bool|null
     *
     * @throws MissingNameArgumentException
     * @throws PackageNotFoundException|FileNotFoundException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

        $this->addServiceReplaceNamespace();

        return parent::handle() && starterKit()->clearCache();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->moreReplaceNamespace ? __DIR__.'/../../../stubs/facade/facade.service.custom.stub' :
            __DIR__.'/../../../stubs/facade/facade.custom.stub';
    }

    /**
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'/Facades';
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
