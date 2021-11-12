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

if (! function_exists('package_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_path(string $path = null): string
    {
        return base_path('packages' . ($path ? '/' . $path : null));
    }
}

if (! function_exists('package_app_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_app_path(string $path = null): string
    {
        return package_path($path) . '/src';
    }
}

