<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * Class GitlabCIMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class GitlabCIMakeCommand extends Command
{
    use UsesVendorPackage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:gitlab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Gitlab CI YML file in a specific package.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageArguments();
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws PackageNotFoundException|JsonException
     */
    public function handle(): int
    {
        $this->setVendorAndPackage();

        $file = '.gitlab-ci.yml';

        $packagePath = package_path($this->package_dir);

        $source = __DIR__ . '/../../../stubs/' . $file;

        $target = $packagePath . '/' . $file;

        if (file_exists($target) === FALSE) {
            return File::copy($source, $target);
        }

        $this->warn('File already exists!');

        return false;
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return null;
    }
}
