<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

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

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return void
     */
    public function handle(): void
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

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
//     * Write the Markdown template for the mailable.
//     *
//     * @return void
//     */
//    protected function writeMarkdownTemplate()
//    {
//        $path = $this->viewPath(
//            str_replace('.', '/', $this->getView()).'.blade.php'
//        );
//
//        if (! $this->files->isDirectory(dirname($path))) {
//            $this->files->makeDirectory(dirname($path), 0755, true);
//        }
//
//        $this->files->put($path, file_get_contents(__DIR__.'/stubs/markdown.stub'));
//    }

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
