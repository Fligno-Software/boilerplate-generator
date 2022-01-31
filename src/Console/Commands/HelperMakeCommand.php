<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesContainerTrait;
use Fligno\BoilerplateGenerator\Traits\UsesOverwriteFileTrait;
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
    use UsesContainerTrait, UsesOverwriteFileTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'gen:helper';

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
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();

        $this->addContainerOptions();

        $this->addOverwriteFileOptions();
    }

    /**
     * @return bool|null
     * @throws MissingNameArgumentException
     * @throws PackageNotFoundException|FileNotFoundException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        if ($this->option('container')) {
            $this->addContainerReplaceNamespace();
        }

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->moreReplaceNamespace ? __DIR__ . '/../../../stubs/helper.container.custom.stub' : __DIR__ . '/../../../stubs/helper.custom.stub';
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
        $classType = $this->getClassType();
        $name = trim($this->argument('name'));

        if ($classType) {
            return Str::of($name)->before($classType)->append($classType)->snake('-');
        }

        return $name;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $path = $this->package_dir ? package_helpers_path($this->package_dir) : base_path('helpers');

        return $path.DIRECTORY_SEPARATOR.str_replace('\\', '/', $name).'.php';
    }
}
