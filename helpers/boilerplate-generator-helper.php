<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-09
 */

use Fligno\BoilerplateGenerator\BoilerplateGenerator;
use Illuminate\Support\Collection;

if (! function_exists('boilerplateGenerator')) {
    /**
     * @return BoilerplateGenerator
     */
    function boilerplateGenerator(): BoilerplateGenerator
    {
        return resolve('boilerplate-generator');
    }
}

if (! function_exists('boilerplate_generator')) {
    /**
     * @return BoilerplateGenerator
     */
    function boilerplate_generator(): BoilerplateGenerator
    {
        return boilerplateGenerator();
    }
}

if (! function_exists('package_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_path(string $path = null, string $domain = null): string
    {
        return base_path(collect(['packages', $path, ($domain ? 'src/Domains/'.$domain : null)])
            ->filter()
            ->implode('/'));
    }
}

// App Path

if (! function_exists('package_app_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_app_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/src';
    }
}

// Database Paths

if (! function_exists('package_database_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_database_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/database';
    }
}

if (! function_exists('package_migration_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_migration_path(string $path = null, string $domain = null): string
    {
        return package_database_path($path, $domain).'/migrations';
    }
}

if (! function_exists('package_seeder_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_seeder_path(string $path = null, string $domain = null): string
    {
        return package_database_path($path, $domain).'/seeders';
    }
}

if (! function_exists('package_factory_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_factory_path(string $path = null, string $domain = null): string
    {
        return package_database_path($path, $domain).'/factories';
    }
}

// Resource Paths

if (! function_exists('package_resource_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_resource_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/resources';
    }
}

if (! function_exists('package_view_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_view_path(string $path = null, string $domain = null): string
    {
        return package_resource_path($path, $domain).'/views';
    }
}

if (! function_exists('package_lang_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_lang_path(string $path = null, string $domain = null): string
    {
        return package_resource_path($path, $domain).'/lang';
    }
}

// Test Paths

if (! function_exists('package_test_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_test_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/tests';
    }
}

// Route Paths

if (! function_exists('package_routes_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_routes_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/routes';
    }
}

// Helper Paths

if (! function_exists('package_helpers_path')) {
    /**
     * @param string|null $path
     * @param string|null $domain
     * @return string
     */
    function package_helpers_path(string $path = null, string $domain = null): string
    {
        return package_path($path, $domain).'/helpers';
    }
}

/***** COMPOSER JSON RELATED *****/

if (! function_exists('set_contents_to_composer_json')) {
    /**
     * @param Collection|array $contents
     * @param string|null $path
     * @return bool
     */
    function set_contents_to_composer_json(Collection|array $contents, string $path = null): bool
    {
        $path = qualify_composer_json($path);

        // Encode associative array to string (prevent escaped slashes)
        $encoded = json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Save to actual composer.json
        return file_put_contents($path, $encoded) !== false;
    }
}

if (! function_exists('setContentsToComposerJson')) {
    /**
     * @param Collection|array $contents
     * @param string|null $path
     * @return bool
     */
    function setContentsToComposerJson(Collection|array $contents, string $path = null): bool
    {
        return set_contents_to_composer_json($contents, $path);
    }
}
