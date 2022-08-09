<?php

namespace Fligno\BoilerplateGenerator\Exceptions;

use Exception;

/**
 * Class MissingNameArgumentException
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class MissingNameArgumentException extends Exception
{
    public function __construct()
    {
        parent::__construct('Name argument is required!');
    }
}
