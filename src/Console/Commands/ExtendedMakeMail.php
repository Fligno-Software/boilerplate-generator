<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * Class ExtendedMakeMail
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeMail extends MailMakeCommand
{
    use UsesCreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new email class in Laravel or in a specific package.';

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
     * @throws PackageNotFoundException|JsonException
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
        $mailStub = __DIR__ . '/../../../stubs/mail.custom.stub';
        $markdownMailStub = __DIR__ . '/../../../stubs/markdown-mail.custom.stub';

        if (
            File::exists($mailStub) === FALSE ||
            File::exists($markdownMailStub) === FALSE
        ) {
            return parent::getStub();
        }

        return $this->option('markdown') !== FALSE ? $markdownMailStub : $mailStub;
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Mail';
    }
}
