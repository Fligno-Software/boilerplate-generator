<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;

/**
 * Class ModelListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class ModelListCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:model:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all models within Laravel or within a specific package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageDomainOptions();
    }

    /**
     * @return int
     *
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): int
    {
        $this->setVendorPackageDomain(true, false);

        $domains = starterKit()->getDomains($this->package_dir);

        $this->table(
            ['Domain', 'Path'],
            $domains?->mapWithKeys(fn ($item, $key) => [[$key, $item]]) ?? []
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
}
