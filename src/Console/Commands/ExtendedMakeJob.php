<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * Class ExtendedMakeJob
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeJob extends JobMakeCommand
{
    use UsesCreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class in Laravel or in a specific package.';

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
     * @throws FileNotFoundException|PackageNotFoundException|JsonException
     */
    public function handle(): ?bool
    {
        $this->setVendorAndPackage();

        return parent::handle();
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->getValidatedNameInput('Job');
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $jobStub = '/../../../stubs/job.custom.stub';
        $queuedJobStub = '/../../../stubs/job.queued.custom.stub';

        if (
            File::exists(__DIR__ . $jobStub) === FALSE ||
            File::exists(__DIR__ . $queuedJobStub) === FALSE
        ) {
            return parent::getStub();
        }

        return $this->option('sync')
            ? __DIR__ . $jobStub
            : __DIR__ . $queuedJobStub;
    }
}
