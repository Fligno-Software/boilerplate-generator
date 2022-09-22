<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandServiceTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class HelperMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class HelperMakeCommand extends GeneratorCommand
{
    use UsesCommandServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:make:helper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper file in Laravel or in a specific package.';

    /**
     * @var string
     */
    protected $type = 'Helper';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions(true, true);

        $this->addServiceOptions();
    }

    /**
     * @return bool|null
     *
     * @throws MissingNameArgumentException|PackageNotFoundException|FileNotFoundException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

        if ($this->getServiceFromOptions()) {
            $this->addServiceReplaceNamespace();
        }

        return parent::handle() && starterKit()->clearCache();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->moreReplaceNamespace ?
            __DIR__.'/../../../stubs/helper/helper.service.custom.stub' :
            __DIR__.'/../../../stubs/helper/helper.custom.stub';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Helper';
    }

    /**
     * Get the validated desired class name from the input.
     *
     * @return string
     */
    protected function getValidatedNameInput(): string
    {
        $classType = Str::snake($this->getClassType(), '-');
        $name = trim($this->argument('name'));

        if ($classType) {
            return Str::of($name)->snake('-')->before($classType)->trim('-')->append('-', $classType);
        }

        return $name;
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        if ($this->domain_dir) {
            return ($this->package_dir ? package_domain_app_path($this->package_dir) :
                    app_path()).'/'.$this->domain_dir.'/helpers';
        }

        return $this->package_dir ? package_domain_helpers_path($this->package_dir) : base_path('helpers');
    }
}
