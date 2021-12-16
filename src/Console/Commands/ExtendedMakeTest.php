<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesEloquentModel;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JsonException;

/**
 * Class ExtendedMakeTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ExtendedMakeTest extends TestMakeCommand
{
    use UsesVendorPackage, UsesEloquentModel;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test class in Laravel or in a specific package.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * Override Constructor to add model option.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addModelOptions();

        $this->addPackageOptions();
    }

    /**
     * @return bool|null
     * @throws FileNotFoundException|PackageNotFoundException|JsonException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        $this->setModelFields();

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/test' . ($this->option('unit') ? '.unit' : null). ($this->option('model') ? '.model' : null) . '.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
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

        $path = $this->package_dir ? package_test_path($this->package_dir).DIRECTORY_SEPARATOR : base_path('tests');

        return $path.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Test';
    }
}
