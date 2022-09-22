<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-09
 */

use Fligno\BoilerplateGenerator\BoilerplateGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

if (! function_exists('parse_domain')) {
    /**
     * @param  string  $domain
     * @param  bool  $as_namespace
     * @param  string  $separator
     * @return string
     */
    function parse_domain(string $domain, bool $as_namespace = false, string $separator = '.'): string
    {
        $replace = ! $as_namespace ? '/domains/' : '\\Domains\\';

        return Str::of($domain)->start($separator)->replace('.', $replace)->jsonSerialize();
    }
}

/***** PATHS *****/

// Base Path

if (! function_exists('package_domain_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        $path = trim($path, '/');
        $domain = $domain ? trim($parse_domain ? parse_domain($domain) : $domain, '/') : null;

        return base_path(
            collect()
                ->when($path, fn (Collection $collection) => $collection->merge(['packages', $path]))
                ->when($domain, fn (Collection $collection) => $collection->add($domain))
                ->filter()
                ->implode('/')
        );
    }
}

// App Path

if (! function_exists('package_domain_app_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_app_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).($path || $domain ? '/src' : '/app');
    }
}

// Database Path

if (! function_exists('package_domain_database_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_database_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).'/database';
    }
}

// Migrations Path

if (! function_exists('package_domain_migrations_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_migrations_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($path, $domain, $parse_domain).'/migrations';
    }
}

// Seeders Path

if (! function_exists('package_domain_seeders_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_seeders_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($path, $domain, $parse_domain).'/seeders';
    }
}

// Factories Path

if (! function_exists('package_domain_factories_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_factories_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($path, $domain, $parse_domain).'/factories';
    }
}

// Resources Path

if (! function_exists('package_domain_resources_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_resources_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).'/resources';
    }
}

// Views Path

if (! function_exists('package_domain_views_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_views_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_resources_path($path, $domain, $parse_domain).'/views';
    }
}

// Lang Path

if (! function_exists('package_domain_lang_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_lang_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_resources_path($path, $domain, $parse_domain).'/lang';
    }
}

// Tests Path

if (! function_exists('package_domain_tests_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_tests_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).'/tests';
    }
}

// Routes Path

if (! function_exists('package_domain_routes_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_routes_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).'/routes';
    }
}

// Helpers Path

if (! function_exists('package_domain_helpers_path')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_helpers_path(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($path, $domain, $parse_domain).'/helpers';
    }
}

/***** NAMESPACES *****/

// Base Namespace

if (! function_exists('package_domain_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string|null
     */
    function package_domain_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string|null
    {
        $vendor = $package = null;

        if ($path) {
            [$vendor, $package] = explode('/', $path);
            $vendor = Str::studly($vendor);
            $package = Str::studly($package);
        }

        $domain = $domain ? trim($parse_domain ? parse_domain($domain, true) : $domain, '/') : null;

        return collect()
            ->when($vendor && $package, fn (Collection $collection) => $collection->merge([$vendor, $package]))
            ->when($domain, fn (Collection $collection) => $collection->add(ltrim($domain, '\\')))
            ->filter()
            ->implode('\\')
            ?: null;
    }
}

// App Namespace

if (! function_exists('package_domain_app_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_app_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return (package_domain_namespace($path, $domain, $parse_domain) ?? 'App').'\\';
    }
}

// Database Namespace

if (! function_exists('package_domain_database_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_database_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return collect([package_domain_namespace($path, $domain, $parse_domain), 'Database'])->filter()->implode('\\');
    }
}

// Seeders Namespace

if (! function_exists('package_domain_seeders_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_seeders_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_namespace($path, $domain, $parse_domain).'\\Seeders\\';
    }
}

// Factories Namespace

if (! function_exists('package_domain_factories_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_factories_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_namespace($path, $domain, $parse_domain).'\\Factories\\';
    }
}

// Tests Namespace

if (! function_exists('package_domain_tests_namespace')) {
    /**
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_tests_namespace(string $path = null, string $domain = null, bool $parse_domain = false): string
    {
        return collect([package_domain_namespace($path, $domain, $parse_domain), 'Tests'])->filter()->implode('\\').'\\';
    }
}

/***** COMPOSER JSON RELATED *****/

if (! function_exists('set_contents_to_composer_json')) {
    /**
     * @param  Collection|array  $contents
     * @param  string|null  $path
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
     * @param  Collection|array  $contents
     * @param  string|null  $path
     * @return bool
     */
    function setContentsToComposerJson(Collection|array $contents, string $path = null): bool
    {
        return set_contents_to_composer_json($contents, $path);
    }
}
