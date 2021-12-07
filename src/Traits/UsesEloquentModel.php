<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait UsesModel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesEloquentModel
{
    use UsesVendorPackage;

    /**
     * @var bool
     */
    protected bool $model_exists = false;

    /**
     * @var bool
     */
    protected bool $skip_model_check = false;

    /**
     * @var string|null
     */
    protected ?string $model_name = null;

    /**
     * @var string|null
     */
    protected ?string $model_class = null;

    /**
     * @var string|null
     */
    protected ?string $model_snake = null;

    /**
     * @var string|null
     */
    protected ?string $model_kebab = null;

    /**
     * Add the standard command options for generating matching tests.
     *
     * @return void
     */
    protected function addModelOptions(): void
    {
        if ($this->getDefinition()->hasOption('model') === FALSE) {
            $this->getDefinition()->addOption(new InputOption('model', 'm', InputOption::VALUE_REQUIRED, 'Specify model to consider.'));
        }
        $this->getDefinition()->addOption(new InputOption('skip', 's', InputOption::VALUE_NONE, 'Skip model existence check. Use if Laravel fails to verify a legitimate Eloquent Model class.'));
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @return void
     */
    public function setModelFields(): void
    {
        $this->skip_model_check = $this->option('skip');

        if ($this->hasOption('model') && $model = $this->option('model')) {
            $model_copy = $model;
            if ($this->model_exists = (is_eloquent_model($model) || is_eloquent_model($model = $this->package_namespace . 'Models' . '\\' . $model) || $this->skip_model_check)) {
                $this->setModelClass($model);
                $this->setModelName($model);
                $this->setModelKebab($model_copy);
                $this->setModelSnake($model_copy);
            }
        }
    }

    /**
     * @param string|null $model_class
     */
    public function setModelClass(?string $model_class): void
    {
        $this->model_class = $model_class;
    }

    /**
     * @return string|null
     */
    public function getModelClass(): ?string
    {
        return $this->model_class;
    }

    /**
     * @param string|null $str
     */
    public function setModelSnake(?string $str): void
    {
        $this->model_snake = Str::snake($str);
    }

    /**
     * @return string|null
     */
    public function getModelSnake(): ?string
    {
        return $this->model_snake;
    }

    /**
     * @param string|null $str
     */
    public function setModelKebab(?string $str): void
    {
        $this->model_kebab = Str::kebab($str);
    }

    /**
     * @return string|null
     */
    public function getModelKebab(): ?string
    {
        return $this->model_kebab;
    }

    /**
     * @param string|null $model_name
     */
    public function setModelName(?string $model_name): void
    {
        $this->model_name = Str::of($model_name)->afterLast('\\');
    }

    /**
     * @return string|null
     */
    public function getModelName(): ?string
    {
        return $this->model_name;
    }

    /**
     * @return array
     */
    public function getEloquentModelArgs(): array
    {
        $args = [];

        if ($this->option('model')) {
            $args['--model'] = $this->getModelClass();
        }

        return $args;
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * Overriding to inject more namespace.
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): static
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel', 'ModelName', 'ModelClass', 'ModelKebab', 'ModelSnake'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}', '{{ model_name }}', '{{ model_class }}', '{{ model_kebab }}', '{{ model_snake }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}', '{{model_name}}', '{{model_class}}', '{{model_kebab}}', '{{model_snake}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->getRootNamespaceDuringReplaceNamespace(), $this->userProviderModel(), $this->getModelName(), $this->getModelClass(), $this->getModelKebab(), $this->getModelSnake()],
                $stub
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getRootNamespaceDuringReplaceNamespace(): string
    {
        return $this->rootNamespace();
    }
}
