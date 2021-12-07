<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

/**
 * Class ExtendedMakeListener
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeListener extends ListenerMakeCommand
{
    use UsesCreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class in Laravel or in a specific package.';

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
        $listenerQueuedStub = __DIR__ . '/../../../stubs/listener-queued.custom.stub';
        $listenerQueuedDuckStub = __DIR__ . '/../../../stubs/listener-queued-duck.custom.stub';
        $listenerStub = __DIR__ . '/../../../stubs/listener.custom.stub';
        $listenerDuckStub = __DIR__ . '/../../../stubs/listener-duck.custom.stub';

        if (
            File::exists($listenerQueuedStub) === FALSE ||
            File::exists($listenerQueuedDuckStub) === FALSE ||
            File::exists($listenerStub) === FALSE ||
            File::exists($listenerDuckStub) === FALSE
        ) {
            return parent::getStub();
        }

        if ($this->option('queued')) {
            return $this->option('event')
                ? $listenerQueuedStub
                : $listenerQueuedDuckStub;
        }

        return $this->option('event')
            ? $listenerStub
            : $listenerDuckStub;
    }
}
