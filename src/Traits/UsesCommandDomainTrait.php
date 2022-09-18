<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Fligno\BoilerplateGenerator\Console\Commands\RouteMakeCommand;
use Fligno\StarterKit\Traits\UsesCommandCustomMessagesTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesCommandDomainTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesCommandDomainTrait
{
    use UsesCommandCustomMessagesTrait;

    /**
     * @var string|null
     */
    protected ?string $domain_name = null;

    /**
     * @var string|null
     */
    protected ?string $domain_dir = null;

    /**
     * @var string|null
     */
    protected ?string $domain_namespace = null;

    /**
     * @var string
     */
    protected string $default_domain = 'none';

    /**
     * @var bool
     */
    protected bool $ddd_enabled = true;

    /**
     * @param bool $has_force_domain
     * @return void
     */
    public function addDomainOptions(bool $has_force_domain = true): void
    {
        $this->ddd_enabled = true;

        $this->getDefinition()->addOption(
            new InputOption(
                'domain',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Domain or module name'
            )
        );

        if ($has_force_domain) {
            $this->getDefinition()->addOption(
                new InputOption(
                    'force-domain',
                    null,
                    InputOption::VALUE_NONE,
                    'Create domain if does not exist.'
                )
            );
        }
    }

    /**
     * @return bool
     */
    protected function shouldCreateDomain(): bool
    {
        return $this->hasOption('force-domain') && $this->option('force-domain');
    }

    /**
     * @return array
     */
    public function getDomainArgs(): array
    {
        $args = [];

        if ($this->domain_name) {
            $args['--domain'] = $this->domain_name;
            $args['--force-domain'] = $this->shouldCreateDomain();
        }

        return $args;
    }

    /**
     * @param string $package_option_name
     * @param string|null $package_dir
     * @param string|null $package_namespace
     * @return void
     */
    public function setDomainFieldsFromOptions(
        string $package_option_name,
        string $package_dir = null,
        string $package_namespace = null
    ): void
    {
        if ($this->domain_name = $this->getDomainFromOptions($package_option_name, $package_dir)) {
            $this->domain_dir = 'Domains/'.$this->domain_name;
            $this->domain_namespace = ($package_namespace ?: 'App\\').'Domains\\'.$this->domain_name.'\\';
        }
    }

    /**
     * @param string $package_option_name
     * @param string|null $package_dir
     * @return array|bool|string|null
     */
    public function getDomainFromOptions(string $package_option_name, string $package_dir = null): bool|array|string|null
    {
        $domain = $this->hasOption('domain') ? trim($this->option('domain')) : null;

        if ($domain === $this->default_domain) {
            return null;
        }

        $domain = $domain ? Str::studly($domain) : null;
        $domain_choices = starterKit()->getDomains($package_dir)?->keys();

        if ($this->ddd_enabled && ($domain || $domain_choices)) {
            // domain IS NOT NULL and domain list IS NOT EMPTY
            if ($domain && $domain_choices) {
                if (($this instanceof RouteMakeCommand && $this->shouldCreateDomain()) || $domain_choices->contains($domain)) {
                    return $domain;
                }

                $this->failed('Domain not found: ' . $domain);
                $choice = $this->choice(
                    'Choose what to do',
                    [
                        'create new domain',
                        'choose from domains',
                    ],
                    0
                );

                if ($choice === 'create new domain') {
                    return $this->createNewDomain($domain, $package_option_name, $domain_choices, $package_dir);
                }

                return $this->chooseFromDomains($domain_choices, $package_dir);
            }

            // domain IS NULL and domain list IS NOT EMPTY
            if ($domain_choices) {
                return $this->chooseFromDomains($domain_choices, $package_dir);
            }

            // domain IS NOT NULL and domain list IS EMPTY
            return $this->createNewDomain($domain, $package_option_name, $domain_choices, $package_dir);
        }

        return null;
    }

    /**
     * @param string $domain
     * @param string $package_option_name
     * @param Collection|null $domain_choices
     * @param string|null $package_dir
     * @return string
     */
    protected function createNewDomain(string $domain, string $package_option_name, Collection $domain_choices = null, string $package_dir = null): string
    {
        $domain = trim($this->ask('Enter new domain name', $domain));

        if ($domain === $this->default_domain) {
            $domain = null;
        }

        $domain = $domain ? Str::studly($domain) : null;

        // create domain if it does not exist
        if ($domain) {
            if (! $this instanceof RouteMakeCommand && ! ($domain_choices?->contains($domain))) {
                $args = array_merge(
                    [
                        '--'.$package_option_name => $package_dir,
                    ],
                    $this->getDomainArgs()
                );
                $args['name'] = $domain;
                $this->call('bg:domain:create', $args);
            }

            return $domain;
        }

        $this->error('Failed to create new domain.');

        return $this->createNewDomain($domain, $package_option_name, $package_dir);
    }

    /**
     * @param Collection|null $domain_choices
     * @param string|null $package_dir
     * @return string|null
     */
    public function chooseFromDomains(Collection $domain_choices = null, string $package_dir = null): string|null
    {
        if ($domain_choices &&
            ($domain = $this->choice(
                'Choose a domain',
                $domain_choices->prepend($this->default_domain)->toArray(),
                0
            ))
        ) {
            if ($domain === $this->default_domain) {
                return null;
            }

            return $domain;
        }

        return null;
    }
}
