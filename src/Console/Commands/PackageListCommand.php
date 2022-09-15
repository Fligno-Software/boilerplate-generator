<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class PackageListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-12-06
 */
class PackageListCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bg:package:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all locally installed packages.';

    public function __construct()
    {
        parent::__construct();

        $this->getDefinition()->addOptions(
            [
                new InputOption(
                    'local',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Whether or not to show local packages.'
                ),
                new InputOption(
                    'enabled',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Whether or not to show enabled packages.'
                ),
                new InputOption(
                    'loaded',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Whether or not to show loaded packages.'
                ),
                new InputOption(
                    'filter',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Filter package by name.'
                ),
            ]
        );
    }

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        $validateBoolean = function(string $key): bool|null {
            if ($option = $this->option($key)) {
                return filter_var($option, FILTER_VALIDATE_BOOLEAN);
            }
            return null;
        };

        // Handle is_local, is_enabled, is_loaded, and filter
        $is_local = $validateBoolean('local');
        $is_enabled = $validateBoolean('enabled');
        $is_loaded = $validateBoolean('loaded');
        $filter = $this->option('filter');

        $this->table(
            [
                'Package',
                'Path',
                'Is Local?',
                'Is Enabled?',
                'Is Loaded?',
            ],
            $this->getPackagesRows($filter, $is_local, $is_enabled, $is_loaded)
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

    /**
     * @param string|null $filter
     * @param bool|null $is_local
     * @param bool|null $is_enabled
     * @param bool|null $is_loaded
     * @return array
     */
    public function getPackagesRows(string $filter = null, bool $is_local = null, bool $is_enabled = null, bool $is_loaded = null): array
    {
        $yes = '<fg=white;bg=green> YES </>';
        $no = '<fg=white;bg=red> NO </>';
        return boilerplateGenerator()
            ->getSummarizedPackages($filter, $is_local, $is_enabled, $is_loaded)
            ->map(function (array $arr, string $package) use ($yes, $no) {
                return [
                    $package,
                    $arr['path'],
                    $arr['is_local'] ? $yes : $no,
                    $arr['is_enabled'] ? $yes : $no,
                    $arr['is_loaded'] ? $yes : $no
                ];
            })
            ->toArray();
    }
}
