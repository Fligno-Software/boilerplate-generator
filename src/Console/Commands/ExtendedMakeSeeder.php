<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeSeeder
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-15
 */
class ExtendedMakeSeeder extends SeederMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:make:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class in Laravel or in a specific package.';

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
        return __DIR__.'/../../../stubs/seeder/seeder.custom.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::of($name)
            ->replaceFirst($this->rootNamespace(), '')
            ->after('Database\\Seeders\\')
            ->replace('\\', '/')
            ->finish('Factory')
            ->jsonSerialize();

        $path = $this->getPackageDomainFullPath();

        if (is_dir($path.'/seeds')) {
            return $path.'/seeds/'.$name.'.php';
        }

        return $path.'/seeders/'.$name.'.php';
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Database\\Seeders';
    }

    /**
     * @return string
     */
    protected function getRootNamespaceDuringReplaceNamespace(): string
    {
        $rootNameSpace = $this->rootNamespace();

        if ($rootNameSpace !== $this->package_namespace) {
            $rootNameSpace = '';
        }

        return $rootNameSpace;
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Seeder';
    }

    /**
     * @return string
     */
    protected function getPackageDomainFullPath(): string
    {
        if ($this->domain_dir) {
            return ($this->package_dir ? package_domain_app_path($this->package_dir).'/'.$this->domain_dir :
                    app_path($this->domain_dir)).'/database';
        }

        return $this->package_dir ? package_domain_database_path($this->package_dir) : database_path();
    }

    /**
     * Overridden from SeederMakeCommand
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name): string
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }
}
