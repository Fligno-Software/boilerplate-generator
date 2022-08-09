<?php

namespace Fligno\BoilerplateGenerator\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Class PackageNotFoundException
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class PackageNotFoundException extends Exception
{
    #[Pure]
    public function __construct(string $package_name)
    {
        parent::__construct('Package does not exist: '.$package_name);
    }
}
