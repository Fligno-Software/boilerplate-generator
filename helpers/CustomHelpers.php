<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */

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

// App Path

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

// Database Paths

if (! function_exists('package_database_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_database_path(string $path = null): string
    {
        return package_path($path) . '/database';
    }
}

if (! function_exists('package_migration_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_migration_path(string $path = null): string
    {
        return package_path($path) . '/database/migrations';
    }
}

if (! function_exists('package_seeder_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_seeder_path(string $path = null): string
    {
        return package_path($path) . '/database/seeders';
    }
}

if (! function_exists('package_factory_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_factory_path(string $path = null): string
    {
        return package_path($path) . '/database/factories';
    }
}

if (! function_exists('package_database_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_database_path(string $path = null): string
    {
        return package_path($path) . '/database';
    }
}

// Resource Paths

if (! function_exists('package_resource_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_resource_path(string $path = null): string
    {
        return package_path($path) . '/resources';
    }
}

if (! function_exists('package_view_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_view_path(string $path = null): string
    {
        return package_path($path) . '/resources/views';
    }
}

// Test Paths

if (! function_exists('package_test_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_test_path(string $path = null): string
    {
        return package_path($path) . '/tests';
    }
}

