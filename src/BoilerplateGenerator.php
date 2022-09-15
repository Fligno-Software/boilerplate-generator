<?php

namespace Fligno\BoilerplateGenerator;

use Fligno\StarterKit\Traits\HasTaggableCacheTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class BoilerplateGenerator
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

class BoilerplateGenerator
{
    use HasTaggableCacheTrait;

    /**
     * @var ?string
     */
    protected ?string $author_name = null;

    /**
     * @var ?string
     */
    protected ?string $author_email = null;

    /**
     * @var ?string
     */
    protected ?string $author_homepage = null;

    /**
     * @return string
     */
    public function getMainTag(): string
    {
        return 'bg';
    }

    /***** CONFIG RELATED *****/

    /**
     * @return bool
     */
    public function isPestEnabled(): bool
    {
        return config('boilerplate-generator.pest_enabled');
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->author_name ?? config('boilerplate-generator.author.name');
    }

    /**
     * @param string|null $author_name
     */
    public function setAuthorName(?string $author_name): void
    {
        $this->author_name = $author_name;
    }

    /**
     * @return string
     */
    public function getAuthorEmail(): string
    {
        return $this->author_email ?? config('boilerplate-generator.author.email');
    }

    /**
     * @param string|null $author_email
     */
    public function setAuthorEmail(?string $author_email): void
    {
        $this->author_email = $author_email;
    }

    /**
     * @return string
     */
    public function getAuthorHomepage(): string
    {
        return $this->author_homepage ?? config('boilerplate-generator.author.homepage');
    }

    /**
     * @param string|null $author_homepage
     */
    public function setAuthorHomepage(?string $author_homepage): void
    {
        $this->author_homepage = $author_homepage;
    }

    /***** PACKAGE RELATED *****/

    /**
     * @return Collection
     */
    public function getLocalPackages(): Collection
    {
        if (file_exists(package_path())) {
            return collect(File::directories(package_path()))->map(function ($vendor_path) {
                $vendor = Str::afterLast($vendor_path, '/');
                return collect(File::directories($vendor_path))->mapWithKeys(function ($package_path) use ($vendor) {
                    $package = Str::afterLast($package_path, '/');
                    return [$vendor . '/' . $package => $package_path];
                });
            })->collapse();
        }

        return collect();
    }

    /**
     * @param string $package
     * @return bool
     */
    public function isPackageLocal(string $package): bool
    {
        return $this->getLocalPackages()->has($package);
    }

    /**
     * Get all the packages installed with Package.
     *
     * @return Collection
     */
    public function getEnabledPackages(): Collection
    {
        $packagesPath = base_path('packages/');
        $repositories = collect(getContentsFromComposerJson()->get('repositories', []));
        $pattern = '{'.addslashes($packagesPath).'(.*)$}';

        return $repositories->mapWithKeys(function ($repository) use ($pattern) {
            if (isset($repository['url']) && preg_match($pattern, $repository['url'], $match)) {
                return [$match[1] => $match[0]];
            }

            return [];
        });
    }

    /**
     * @param string $package
     * @return bool
     */
    public function isPackageEnabled(string $package): bool
    {
        return $this->getEnabledPackages()->has($package);
    }

    /**
     * @param bool $with_root
     * @return Collection
     */
    public function getLoadedPackages(bool $with_root = false): Collection
    {
        return starterKit()->getPaths()->map(function ($package, $vendor_name) {
            return collect($package)->mapWithKeys(function ($details, $package_name) use ($vendor_name) {
                return [$vendor_name . '/' . $package_name => $details['path']];
            });
        })
            ->collapse()
            // laravel/laravel represents root composer.json
            ->when(! $with_root, fn(Collection $collection) => $collection->except('laravel/laravel'));
    }

    /**
     * @param string $package
     * @return bool
     */
    public function isPackageLoaded(string $package): bool
    {
        return $this->getLoadedPackages()->has($package);
    }

    /**
     * @param string|array|null $filter
     * @param bool|null $is_local
     * @param bool|null $is_enabled
     * @param bool|null $is_loaded
     * @param bool $with_root
     * @return Collection
     */
    public function getSummarizedPackages(string|array $filter = null, bool $is_local = null, bool $is_enabled = null, bool $is_loaded = null, bool $with_root = false): Collection
    {
        $loaded = $this->getLoadedPackages($with_root);
        $local = $this->getLocalPackages();
        $enabled = $this->getEnabledPackages();

        return $local->merge($loaded)->map(function ($path, $package) use ($local, $loaded, $enabled) {
            return [
                'path' => $path,
                'is_local' => $local->has($package),
                'is_enabled' => $enabled->has($package),
                'is_loaded' => $loaded->has($package),
            ];
        })
            ->when($filter, fn(Collection $collection) => $collection->filter(fn($value, $key) => Str::contains($key, $filter)))
            ->when(! is_null($is_local), fn(Collection $collection) => $collection->filter(fn(array $arr) => $arr['is_local'] == $is_local))
            ->when(! is_null($is_enabled), fn(Collection $collection) => $collection->filter(fn(array $arr) => $arr['is_enabled'] == $is_enabled))
            ->when(! is_null($is_loaded), fn(Collection $collection) => $collection->filter(fn(array $arr) => $arr['is_loaded'] == $is_loaded));
    }

    /**
     * @param string $package
     * @param bool $with_root
     * @return bool
     */
    public function isPackageExisting(string $package, bool $with_root = false): bool
    {
        return $this->getSummarizedPackages(with_root: $with_root)->has($package);
    }
}
