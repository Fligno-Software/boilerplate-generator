<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;

/**
 * Class FlignoDomainListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class FlignoDomainListCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:domain:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageOptions(false);
    }

    /**
     * @return int
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): int
    {
        starterKit()->clearCache();

        $this->setVendorPackageDomain(true, false);

        if ($domains = $this->getAllDomains(true)) {

            $domains = $domains->mapWithKeys(fn($item, $key) => [[$key, $item]]);

            $this->table(
                ['Domain', 'Path'],
                $domains
            );
        }
        else {
            $this->note('No domains found.');
        }

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
