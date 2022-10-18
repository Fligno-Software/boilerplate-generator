<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class PackageCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-09
 */
class PackageCreateCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bg:package:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel package.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct(protected Composer $composer)
    {
        parent::__construct();

        $this->addPackageArguments();
    }

    /**
     * Execute the console command.
     *
     * @throws PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): void
    {
        $this->setVendorPackageDomain(false, false);

        // set the author details of Packager
        config(['packager.author_name' => boilerplateGenerator()->getAuthorName()]);
        config(['packager.author_email' => boilerplateGenerator()->getAuthorEmail()]);
        config(['packager.author_homepage' => boilerplateGenerator()->getAuthorHomepage()]);
        config(['packager.skeleton' => boilerplateGenerator()->getPackageSkeleton()]);

        $this->call(
            'packager:new',
            [
                'vendor' => $this->vendor_name,
                'name' => $this->package_name,
                '--i' => true,
            ]
        );

        // Clear starter kit cache and run composer dump
        starterKit()->clearCache();
        $this->composer->dumpAutoloads();

        // Run all necessary file generators
        collect(['web', 'api'])->each(
            function ($value) {
                $this->call(
                    'bg:make:route',
                    [
                        'name' => $value,
                        '--package' => $this->package_dir,
                        '--api' => $value !== 'web',
                        '--no-interaction' => true,
                    ]
                );
            }
        );

        $this->call(
            'bg:make:gitlab',
            [
                '--package' => $this->package_dir,
                '--no-interaction' => true,
            ]
        );

        $test_directory = Str::of(package_domain_tests_path($this->package_dir))
            ->after(base_path())
            ->replace('\\', '/')
            ->ltrim('/')
            ->jsonSerialize();

        $this->ongoing('Running command: php artisan pest:install --test-directory='.$test_directory, false);

        $this->call(
            'pest:install',
            [
                '--test-directory' => $test_directory,
                '--no-interaction' => true,
            ]
        );

        starterKit()->clearCache();
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            [
                ['yes', 'y', InputOption::VALUE_NONE, 'Yes to all generate questions.'],
            ]
        );
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