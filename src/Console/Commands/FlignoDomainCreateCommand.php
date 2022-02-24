<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FlignoDomainCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class FlignoDomainCreateCommand extends GeneratorCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:domain:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string
     */
    protected $type = 'Domain';

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions(false, false, false);
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): int
    {
        $this->setVendorPackageDomain(true, false);

        $args = $this->getPackageArgs();
        $args['--domain'] = $this->getNameInput();
        $args['--force-domain'] = true;
        $args['--no-interaction'] = true;

        $success = false;

        collect(['web', 'api'])->each(function ($value) use ($args, &$success) {
            $args['name'] = $value;
            $args['--api'] = $value !== 'web';
            if ($this->call('gen:route', $args) === self::SUCCESS) {
                $success = true;
            }
        });

        if ($success) {
            $this->done('Domain created successfully.');
        }
        else {
            $this->failed('Domain was not created or already existing.');
        }

        return $success;
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
}
