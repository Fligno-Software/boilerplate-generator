<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ExceptionMakeCommand;

/**
 * Class ExtendedMakeException
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeException extends ExceptionMakeCommand
{
    use UsesVendorPackage;

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
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $exceptionRenderReportStub = __DIR__ . '/../../../stubs/exception-render-report.custom.stub';
        $exceptionRenderStub = __DIR__ . '/../../../stubs/exception-render.custom.stub';
        $exceptionReportStub = __DIR__ . '/../../../stubs/exception-report.custom.stub';
        $exceptionStub = __DIR__ . '/../../../stubs/exception.custom.stub';

        if (
            file_exists($exceptionRenderReportStub) === FALSE ||
            file_exists($exceptionRenderStub) === FALSE ||
            file_exists($exceptionReportStub) === FALSE ||
            file_exists($exceptionStub) === FALSE
        ) {
            return parent::getStub();
        }

        if ($this->option('render')) {
            return $this->option('report')
                ? $exceptionRenderReportStub
                : $exceptionRenderStub;
        }

        return $this->option('report')
            ? $exceptionReportStub
            : $exceptionStub;
    }
}
