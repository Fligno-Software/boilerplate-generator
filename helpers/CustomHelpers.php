<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */

use Fligno\BoilerplateGenerator\ExtendedResponse;
if (! function_exists('customResponse')) {
    /**
     * @return ExtendedResponse
     */
    function customResponse(): ExtendedResponse
    {
        return resolve('extended-response');
    }
}
