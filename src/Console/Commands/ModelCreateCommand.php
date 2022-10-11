<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ModelCreateCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-09
 */
class ModelCreateCommand extends ExtendedMakeModel
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bg:model:create';
}
