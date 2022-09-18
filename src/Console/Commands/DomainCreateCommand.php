<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DomainCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class DomainCreateCommand extends GeneratorCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:domain:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a domain or module in Laravel or in a specific package.';

    /**
     * @var string
     */
    protected $type = 'Domain';

    /**
     * Create a new controller creator command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageDomainOptions(has_force_domain: false);
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): bool|null
    {
        $this->setVendorPackageDomain(true, false);

        $this->domain_name = $this->getNameInput();

        $this->domain_dir = 'Domains/'.$this->domain_name;
        $this->domain_namespace = ($this->package_namespace ?: 'App\\').'Domains\\'.$this->domain_name.'\\';

        $args = $this->getPackageArgs();
        $args['--domain'] = $this->domain_name;
        $args['--force-domain'] = true;
        $args['--no-interaction'] = true;

        $success = false;

        collect(['web', 'api'])->each(
            function ($value) use ($args, &$success) {
                $args['name'] = $value;
                $args['--api'] = $value !== 'web';
                if ($this->call('bg:make:route', $args) === self::SUCCESS) {
                    $success = true;
                }
            }
        );

        if ($success) {
            $this->done('Domain created successfully.');
            $this->addDomainSeedersFactoriesPathsToComposerJson();
        } else {
            $this->failed('Domain was not created or already existing.');
        }

        return $success && (starterKit()->clearCache() ? self::SUCCESS : self::FAILURE);
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

    /**
     * Get the stub file for the generator.
     *
     * @return string|null
     */
    protected function getStub(): ?string
    {
        return null;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'Domain or module name'],
        ];
    }

    /**
     * @return void
     */
    protected function addDomainSeedersFactoriesPathsToComposerJson(): void
    {
        $this->ongoing('Adding src, factories, and seeders paths to composer.json autoload');
        $path = $this->package_dir ? Str::after(package_path($this->package_dir), base_path()) : null;
        $contents = getContentsFromComposerJson($path)?->toArray();

        if ($contents)
        {
            $namespace = $this->getPackageDomainNamespace();
            $psr4_path = Str::of($this->getPackageDomainFullPath())
                ->after($this->package_dir ? package_path($this->package_dir) : base_path())
                ->ltrim('/')
                ->finish('/');

            $app_src_path = $psr4_path->jsonSerialize();
            $factories_path = $psr4_path->replace(['/app', '/src'], '/database/factories')->jsonSerialize();
            $seeders_path = $psr4_path->replace(['/app', '/src'], '/database/seeders')->jsonSerialize();

            // load src or app folder
            $contents['autoload']['psr-4'][$namespace] = $app_src_path;

            // load factories folder
            $contents['autoload']['psr-4'][$namespace.'Database\\Factories\\'] = $factories_path;

            // load seeders folder
            $contents['autoload']['psr-4'][$namespace.'Database\\Seeders\\'] = $seeders_path;

            // set updated content to composer.json
            if (set_contents_to_composer_json($contents, $path)) {
                $this->done('Added src, factories, and seeders paths to composer.json autoload');
            }
            else {
                $this->failed('Failed to add src, factories, and seeders paths to composer.json autoload');
            }
        }
        else {
            $this->failed('Failed to get contents from composer.json: ' . $path);
        }
    }
}
