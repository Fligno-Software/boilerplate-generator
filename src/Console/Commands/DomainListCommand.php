<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandFilterTrait;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;

/**
 * Class DomainListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class DomainListCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait, UsesCommandFilterTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:domain:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all domains or modules within Laravel or within a specific package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageDomainOptions(has_domain_choices: false);

        $this->addFilterOptions('domain');
    }

    /**
     * @return int
     *
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): int
    {
        $this->setVendorPackageDomain(true, false);

        // Handle is_local, is_enabled, is_loaded, and filter
        $is_local = $this->validateBoolean('local');
        $is_enabled = $this->validateBoolean('enabled');
        $is_loaded = $this->validateBoolean('loaded');
        $filter = $this->option('filter');

        $this->table(
            [
                'Package',
                'Path',
                'Is Local?',
                'Is Enabled?',
                'Is Loaded?',
            ],
            $this->getDomainRows()
        );

        return self::SUCCESS;
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
    public function getDomainRows(): array
    {
        $yes = '<fg=white;bg=green> YES </>';
        $no = '<fg=white;bg=red> NO </>';

        // Handle is_local, is_enabled, is_loaded, and filter
        $is_local = $this->validateBoolean('local');
        $is_enabled = $this->validateBoolean('enabled');
        $is_loaded = $this->validateBoolean('loaded');
        $filter = $this->option('filter');

        return boilerplateGenerator()
            ->getSummarizedDomains($this->package_dir, $filter, $is_local, $is_enabled, $is_loaded)
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
