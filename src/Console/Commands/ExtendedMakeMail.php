<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\MailMakeCommand;

/**
 * Class ExtendedMakeMail
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-20
 */
class ExtendedMakeMail extends MailMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:make:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new email class in Laravel or in a specific package.';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions();
    }

    /*****
     * OVERRIDDEN FUNCTIONS
     *****/

    /**
     * @return void
     *
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain();

        parent::handle();

        starterKit()->clearCache();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('markdown') !== false ? __DIR__ . '/../../../stubs/mail/mail-markdown.custom.stub' :
            __DIR__ . '/../../../stubs/mail/mail.custom.stub';
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
