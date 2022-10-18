<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @since  2021-11-09
 */

use Fligno\BoilerplateGenerator\Services\BoilerplateGenerator;
use Illuminate\Support\Arr;
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

/***** PATHS *****/

// Base Path

if (! function_exists('package_domain_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        $package = trim($package, '/');
        $domain = $domain ? trim($parse_domain ? domain_decode($domain) : $domain, '/') : null;

        return base_path(
            collect()
                ->when($package, fn (Collection $collection) => $collection->merge(['packages', $package]))
                ->when($domain, fn (Collection $collection) => $collection->add($domain))
                ->filter()
                ->implode('/')
        );
    }
}

// App Path

if (! function_exists('package_domain_app_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_app_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).($package || $domain ? '/src' : '/app');
    }
}

// Database Path

if (! function_exists('package_domain_database_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_database_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/database';
    }
}

// Migrations Path

if (! function_exists('package_domain_migrations_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_migrations_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($package, $domain, $parse_domain).'/migrations';
    }
}

// Seeders Path

if (! function_exists('package_domain_seeders_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_seeders_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($package, $domain, $parse_domain).'/seeders';
    }
}

// Factories Path

if (! function_exists('package_domain_factories_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_factories_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_path($package, $domain, $parse_domain).'/factories';
    }
}

// Resources Path

if (! function_exists('package_domain_resources_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_resources_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/resources';
    }
}

// Views Path

if (! function_exists('package_domain_views_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_views_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_resources_path($package, $domain, $parse_domain).'/views';
    }
}

// Lang Path

if (! function_exists('package_domain_lang_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_lang_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_resources_path($package, $domain, $parse_domain).'/lang';
    }
}

// Tests Path

if (! function_exists('package_domain_tests_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_tests_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/tests';
    }
}

// Routes Path

if (! function_exists('package_domain_routes_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_routes_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/routes';
    }
}

// Helpers Path

if (! function_exists('package_domain_helpers_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_helpers_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/helpers';
    }
}

// Domains Path

if (! function_exists('package_domain_domains_path')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_domains_path(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_path($package, $domain, $parse_domain).'/domains';
    }
}

/***** NAMESPACES *****/

// Base Namespace

if (! function_exists('package_domain_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string|null
     */
    function package_domain_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string|null
    {
        $vendor_name = $package_name = null;

        if ($package) {
            [$vendor_name, $package_name] = explode('/', $package);
            $vendor_name = Str::studly($vendor_name);
            $package_name = Str::studly($package_name);
        }

        $domain = $domain ? trim($parse_domain ? domain_decode($domain, true) : $domain, '/') : null;

        return collect()
            ->when($vendor_name && $package_name, fn (Collection $collection) => $collection->merge([$vendor_name, $package_name]))
            ->when($domain, fn (Collection $collection) => $collection->add(ltrim($domain, '\\')))
            ->filter()
            ->implode('\\')
            ?: null;
    }
}

// App Namespace

if (! function_exists('package_domain_app_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_app_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return (package_domain_namespace($package, $domain, $parse_domain) ?? 'App').'\\';
    }
}

// Database Namespace

if (! function_exists('package_domain_database_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_database_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return collect([package_domain_namespace($package, $domain, $parse_domain), 'Database'])->filter()->implode('\\');
    }
}

// Seeders Namespace

if (! function_exists('package_domain_seeders_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_seeders_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_namespace($package, $domain, $parse_domain).'\\Seeders\\';
    }
}

// Factories Namespace

if (! function_exists('package_domain_factories_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_factories_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return package_domain_database_namespace($package, $domain, $parse_domain).'\\Factories\\';
    }
}

// Tests Namespace

if (! function_exists('package_domain_tests_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_tests_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return collect([package_domain_namespace($package, $domain, $parse_domain), 'Tests'])->filter()->implode('\\').'\\';
    }
}

// Domains Namespace

if (! function_exists('package_domain_domains_namespace')) {
    /**
     * @param  string|null  $package
     * @param  string|null  $domain
     * @param  bool  $parse_domain
     * @return string
     */
    function package_domain_domains_namespace(string $package = null, string $domain = null, bool $parse_domain = false): string
    {
        return collect([package_domain_namespace($package, $domain, $parse_domain), 'Domains'])->filter()->implode('\\').'\\';
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

if (! function_exists('add_contents_to_composer_json')) {
    /**
     * @param  string  $dot_notation_key
     * @param  Collection|array|string|bool  $contents
     * @param  string|null  $path
     * @param  bool  $merge_if_array
     * @return bool
     */
    function add_contents_to_composer_json(
        string $dot_notation_key,
        array|string|bool $contents,
        string $path = null,
        bool $merge_if_array = true,
    ): bool {
        $path = qualify_composer_json($path);

        // Convert collection to array to be able to use Arr::get() and Arr::set()
        $old_contents = get_contents_from_composer_json($path)->toArray();
        $new_contents = $old_contents;

        $old_value = Arr::get($new_contents, $dot_notation_key);

        // If already existing and incoming content is array, decide accordingly
        if ($old_value && is_array($contents) && $merge_if_array) {
            $contents = array_merge(Arr::wrap($old_value), $contents);
            if (! Arr::isAssoc($contents)) {
                $contents = array_unique($contents);
            }
        }

        // Set incoming content to new contents array
        Arr::set($new_contents, $dot_notation_key, $contents);

        // Check whether to update composer.json or not
        if ($old_contents != $new_contents) {
            return set_contents_to_composer_json($new_contents, $path);
        }

        return false;
    }
}

if (! function_exists('remove_contents_from_composer_json')) {
    /**
     * @param  string  $dot_notation_key
     * @param  Collection|array|string|bool  $contents
     * @param  string|null  $path
     * @return bool
     */
    function remove_contents_from_composer_json(
        string $dot_notation_key,
        array|string|bool|null $contents = null,
        string $path = null
    ): bool {
        $path = qualify_composer_json($path);

        // Convert collection to array to be able to use Arr::get() and Arr::set()
        $old_contents = get_contents_from_composer_json($path)->toArray();
        $new_contents = $old_contents;

        $old_value = Arr::get($new_contents, $dot_notation_key);

        // If exists and incoming content is array, decide accordingly

        if (! is_null($old_value)) {
            if (is_array($old_value) && $contents) {
                $is_assoc = Arr::isAssoc($old_value);
                $old_value = array_diff($old_value, Arr::wrap($contents));

                if (! $is_assoc) {
                    $old_value = array_values($old_value);
                }

                Arr::set($new_contents, $dot_notation_key, $old_value);
            } else {
                Arr::forget($new_contents, $dot_notation_key);
            }
        }

        // Check whether to update composer.json or not
        if ($old_contents != $new_contents) {
            return set_contents_to_composer_json($new_contents, $path);
        }

        return false;
    }
}
