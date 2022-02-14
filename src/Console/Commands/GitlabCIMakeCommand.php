<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GitlabCIMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class GitlabCIMakeCommand extends Command
{
    use UsesVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'gen:gitlab';

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

        $this->addPackageOptions();
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): int
    {
        $this->setVendorAndPackage();

        $file = '.gitlab-ci.yml';

        $packagePath = package_path($this->package_dir);

        $source = __DIR__ . '/../../../stubs/' . $file;

        $target = $packagePath . '/' . $file;

        if ($this->option('force') || file_exists($target) === FALSE) {
            File::copy($source, $target);

            $this->info('Gitlab file created successfully.');

            return self::SUCCESS;
        }

        $this->warn('Gitlab file already exists!');

        return self::FAILURE;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force create Gitlab CI yml file.']
        ];
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
