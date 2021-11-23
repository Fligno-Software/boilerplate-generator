<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

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

//    /**
//     * Create the matching test case if requested.
//     *
//     * @param  string  $path
//     * @return void
//     */
//    protected function handleTestCreation($path): void
//    {
//        if (! $this->option('test') && ! $this->option('pest')) {
//            return;
//        }
//
//        $args = [
//            'name' => Str::of($path)->after($this->laravel['path'])->beforeLast('.php')->append('Test')->replace('\\', '/'),
//            '--pest' => $this->option('pest'),
//        ];
//
//        if ($this->package_dir) {
//            $args['--package'] = $this->package_dir;
//        }
//
//        $this->call('gen:test', $args);
//    }
}
