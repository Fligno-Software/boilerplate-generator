<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCommandFilterTrait;
use Illuminate\Console\Command;

/**
 * Class PackageListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-12-06
 */
class PackageListCommand extends Command
{
    use UsesCommandFilterTrait;

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

        $this->addFilterOptions('package');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->table(
            [
                'Package',
                'Path',
                'Is Local?',
                'Is Enabled?',
                'Is Loaded?',
            ],
            $this->getPackagesRows()
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
     * @return array
     */
    public function getPackagesRows(): array
    {
        $yes = '<fg=white;bg=green> YES </>';
        $no = '<fg=white;bg=red> NO </>';

        // Handle is_local, is_enabled, is_loaded, and filter
        $is_local = $this->validateBoolean('local');
        $is_enabled = $this->validateBoolean('enabled');
        $is_loaded = $this->validateBoolean('loaded');
        $filter = $this->option('filter');

        return boilerplateGenerator()
            ->getSummarizedPackages($filter, $is_local, $is_enabled, $is_loaded)
            ->map(function (array $arr, string $package) use ($yes, $no) {
                return [
                    $package,
                    $arr['path'],
                    $arr['is_local'] ? $yes : $no,
                    $arr['is_enabled'] ? $yes : $no,
                    $arr['is_loaded'] ? $yes : $no,
                ];
            })
            ->toArray();
    }
}
