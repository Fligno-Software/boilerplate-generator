<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTestTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\NotificationMakeCommand;

/**
 * Class ExtendedMakeNotification
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-17
 */
class ExtendedMakeNotification extends NotificationMakeCommand
{
    use UsesCreatesMatchingTestTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class in Laravel or in a specific package.';

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
     * @return void
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorAndPackage();

        parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../../stubs/notification.custom.stub';
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Notification';
    }
}
