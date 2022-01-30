<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesContainerTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesContainerTrait
{
    use UsesVendorPackageTrait;

    /**
     * @var bool
     */
    protected bool $container_exists = false;

    /**
     * @param string|null $description
     * @return void
     */
    protected function addContainerOptions(string $description = null): void
    {
        $this->getDefinition()->addOption(new InputOption(
            'container', 'c', InputOption::VALUE_REQUIRED, $description ?? 'Service Container class.'
        ));
    }

    /**
     * @param string $container
     * @param string|null $additionalNamespace
     * @param bool $qualifyContainer
     * @param bool $disablePackageNamespaceTemporarily
     * @return bool
     */
    protected function checkContainerExists(string &$container, string $additionalNamespace = null, bool $qualifyContainer = true, bool $disablePackageNamespaceTemporarily = false): bool
    {
        $containerCopy = $container;

        if ($disablePackageNamespaceTemporarily) {
            $this->is_package_namespace_disabled = true;
        }

        if ($qualifyContainer) {
            $container = $this->qualifyContainer($container, $additionalNamespace);
        }
        else {
            $container = (string) $this->cleanClassNamespace($container);
        }

        $this->is_package_namespace_disabled = false;

        if (!($this->container_exists = class_exists($container))) {
            $container = $containerCopy;
        }

        return $this->container_exists;
    }

    /**
     * @return void
     */
    protected function addContainerReplaceNamespace(): void
    {
        if (($container = $this->option('container')) && (
                $this->checkContainerExists($container) ||
                $this->checkContainerExists($container, null, false) ||
                $this->checkContainerExists($container, null, true, true) ||
                $this->checkContainerExists($container, 'Containers') ||
                $this->checkContainerExists($container, 'Containers', false) ||
                $this->checkContainerExists($container, 'Containers', true, true)
            )
        ) {
            $this->addMoreCasedReplaceNamespace($container, 'Container');
        }
    }

    /**
     * Qualify the given model class base name.
     *
     * @param string $container
     * @param string|null $additionalNamespace
     * @return string
     */
    protected function qualifyContainer(string $container, string $additionalNamespace = null): string
    {
        $container = (string) $this->cleanClassNamespace($container);

        $rootContainerNamespace = trim($this->rootNamespace() . $additionalNamespace, '\\');

        if (Str::startsWith($container, $rootContainerNamespace)) {
            return $container;
        }

        return $this->qualifyContainer($rootContainerNamespace . '\\' . $container, $additionalNamespace);
    }
}
