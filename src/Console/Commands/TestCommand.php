<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCommandMultipleTargetsTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class TestCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-17
 */
class TestCommand extends Command
{
    use UsesCommandMultipleTargetsTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bg:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the application and package tests.';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // To ignore validation errors
        $this->ignoreValidationErrors();

        $this->addMultipleTargetsOption();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->targets = $this->getPackageFromOptions(true);

        $domain_search = $this->getDomainFromOption();

        // check if it has default package
        $has_root = $this->targets && in_array($this->default_package, $this->targets);

        // show choices if neither --all or --packages is used
        if (! $this->isRootAndPackages() && ! $this->isPackagesOnly()) {
            $default_choices = $this->targets ? boilerplateGenerator()->getSummarizedPackages($this->targets)
                ->keys()
                // add back 'root' to the list of default choices if previously typed
                ->when(
                    $has_root,
                    fn (Collection $collection) => $collection->prepend($this->default_package)
                )
                ->toArray() :
                [];

            $this->targets = $this->choosePackageFromList(is_loaded: true, multiple: true, default_choices: $default_choices);
        } else {
            $this->targets = null;
        }

        // default behavior
        $test_packages = false;

        // needed checks related to default package
        $has_root = $this->targets && in_array($this->default_package, $this->targets); // check again
        $has_other_than_root = $has_root ? count($this->targets) > 1 : ($this->targets && count($this->targets));

        // if packages is null or has root already
        $test_root = (! $this->targets || $has_root) && ! $this->isPackagesOnly();

        // Decide whether to test packages
        if ($this->isRootAndPackages() || $this->isPackagesOnly() || $has_other_than_root) {
            $test_packages = true;
        }

        // Actual Test Executions
        $test_paths = [];
        $dot_notation = 'directories.tests.path';

        $add_to_test_paths = function (array $domains, string $package = null) use ($domain_search, $dot_notation, &$test_paths) {
            collect($domains)
                ->when($domain_search, fn ($collection) => $collection->filter(fn($details, $domain) => $domain == $domain_search || Str::contains($domain, $domain_search)))
                ->each(function ($details, $domain) use ($package, $dot_notation, &$test_paths) {
                    $test_paths[] = [
                        'package' => $package,
                        'domain' => $domain,
                        'tests_path' => Arr::get($details, $dot_notation)
                    ];
                });
        };

        // Get root and its domains tests paths
        if ($test_root) {
            if (! $domain_search) {
                $test_paths[] = [
                    'package' => null,
                    'domain' => null,
                    'tests_path' => null
                ];
            }
            $domains = starterKit()->getRoot()->get('domains', []);
            $add_to_test_paths($domains);
        }

        // Get packages and each package's domains tests paths
        if ($test_packages) {
            boilerplateGenerator()->getSummarizedPackages(is_loaded: true, with_details: true)
                ->when($has_other_than_root, fn (Collection $collection) => $collection->only($this->targets))
                ->each(function (array $details, string $package) use ($add_to_test_paths, $dot_notation, &$test_paths, $domain_search) {
                    if (! $domain_search) {
                        $test_paths[] = [
                            'package' => $package,
                            'tests_path' => Arr::get($details, $dot_notation)
                        ];
                    }
                    $domains = Arr::get($details, 'domains', []);
                    $add_to_test_paths($domains, $package);
                });
        }

        $progress = $this->output->createProgressBar(count($test_paths));

        // function to run the tests
        foreach ($test_paths as $test_path) {
            $progress->advance();
            $progress->display();
            $this->executeTests(...$test_path);
        }

        $progress->finish();

        return 0;
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
     * @param string|null $package
     * @param string|null $domain
     * @param string|null $tests_path
     * @return void
     */
    public function executeTests(string $package = null, string $domain = null, string $tests_path = null): void
    {
        $get_green_bold_text = function (string $str) {
            return '<green-bold>'.$str.'</green-bold>';
        };

        $message = '🧪 Running tests for ';
        if ($package && $domain) {
            $message .= $get_green_bold_text($domain) . ' domain of ' . $get_green_bold_text($package) . ' 📦';
        }
        elseif ($package) {
            $message .= $get_green_bold_text($package) . ' 📦';
        }
        elseif ($domain) {
            $message .= $get_green_bold_text($domain) . ' domain of ' . $get_green_bold_text('Laravel');
        }
        else {
            $message .= $get_green_bold_text('Laravel');
        }

        $this->newLine(2);
        $this->ongoing($message);

        if (($package || $domain) && ! $tests_path) {
            $this->warning('🔍 Tests folder is not found.');
            $this->newLine();
            return;
        }

        $test_directory = null;

        if ($tests_path) {
            $tests_path = Str::of($tests_path)
                ->after(base_path())
                ->replace('\\', '/')
                ->ltrim('/')
                ->finish('/tests')
                ->jsonSerialize();

            $test_directory = '--test-directory='.$tests_path;
        }

        $command = collect(['php artisan test', $tests_path, $test_directory])->merge($this->collectRawOptions())->filter()->implode(' ');

        $this->ongoing('🏃 Running command️: '.$get_green_bold_text($command), false);

        exec($command);
    }

    /**
     * @return Collection
     */
    protected function collectRawOptions(): Collection
    {
        $argv = collect($_SERVER['argv']);

        // Only get the args that pass
        return $argv->filter(function ($arg) {
            $arg = Str::of($arg)->before('=');

            return $arg->startsWith('--') && ! $arg->contains(['all', 'packages', 'package', 'test-directory', 'domain']);
        });
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            [
                'domain', null, InputOption::VALUE_REQUIRED, 'Apply to a domain and its subdomains.',
            ]
        ];
    }

    /**
     * @return string|null
     */
    public function getDomainFromOption(): ?string
    {
        return $this->option('domain');
    }
}
