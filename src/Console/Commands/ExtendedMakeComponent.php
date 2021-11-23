<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeChannel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeComponent extends ComponentMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view component class in Laravel or in a specific package.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/view-component.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions(false)
        );
    }

    /**
     * Write the view for the component.
     *
     * @return void
     */
    protected function writeView(): void
    {
        $path = str_replace('.', '/', 'components.'.$this->getView()).'.blade.php';

        if ($this->package_dir) {
            $path = package_view_path($this->package_dir) . DIRECTORY_SEPARATOR . $path;
        }
        else {
            $path = $this->viewPath($path);
        }

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error('View already exists!');

            return;
        }

        file_put_contents(
            $path,
            '<div>
<!-- '.Inspiring::quote().' -->
</div>'
        );
    }
}
