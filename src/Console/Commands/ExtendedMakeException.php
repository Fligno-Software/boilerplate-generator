<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeException
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeException extends ExceptionMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:exception';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new custom exception class in Laravel or in a specific package.';

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
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
        $exceptionRenderReportStub = '/../../../stubs/exception-render-report.custom.stub';
        $exceptionRenderStub = '/../../../stubs/exception-render.custom.stub';
        $exceptionReportStub = '/../../../stubs/exception-report.custom.stub';
        $exceptionStub = '/../../../stubs/exception.custom.stub';

        if (
            File::exists(__DIR__ . $exceptionRenderReportStub) === FALSE ||
            File::exists(__DIR__ . $exceptionRenderStub) === FALSE ||
            File::exists(__DIR__ . $exceptionReportStub) === FALSE ||
            File::exists(__DIR__ . $exceptionStub) === FALSE
        ) {
            return parent::getStub();
        }

        if ($this->option('render')) {
            return $this->option('report')
                ? __DIR__ . $exceptionRenderReportStub
                : __DIR__ . $exceptionRenderStub;
        }

        return $this->option('report')
            ? __DIR__ . $exceptionReportStub
            : __DIR__ . $exceptionStub;
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
}
