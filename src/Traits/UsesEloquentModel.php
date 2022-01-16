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
            if ($this->skip_model_check ||
                ($this->isGeneratorSubclass() && (
                    $this->checkModelExists($model, false) ||
                    $this->checkModelExists($model, true, true) ||
                    $this->checkModelExists($model)
                    )
                )
            ) {
                $this->setModelClass($model);
                $this->setModelName($model);
                $this->setModelKebab($model);
                $this->setModelSnake($model);
            }
        }
    }

    /**
     * @param string|null $str
     */
    public function setModelClass(?string $str): void
    {
        $this->model_class = $str;

        $this->addMoreReplaceNamespace([
            'ModelClass' => $this->model_class
        ]);
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

        $this->addMoreReplaceNamespace([
            'ModelSnake' => $this->model_snake
        ]);
    }

    /**
     * @param string|null $str
     */
    public function setModelKebab(?string $str): void
    {
        $this->model_kebab = Str::kebab($str);

        $this->addMoreReplaceNamespace([
            'ModelKebab' => $this->model_kebab
        ]);
    }

    /**
     * @param string|null $str
     */
    public function setModelName(?string $str): void
    {
        $this->model_name = Str::of($str)->afterLast('\\');

        $this->addMoreReplaceNamespace([
            'ModelName' => $this->model_name
        ]);
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

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel(string $model): string
    {
        $model = (string) $this->cleanClassNamespace($model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models'))
            ? $rootNamespace.'Models\\'.$model
            : $rootNamespace.$model;
    }

    /**
     * @param string $model
     * @param bool $qualifyModel
     * @param bool $disablePackageNamespaceTemporarily
     * @return bool
     */
    protected function checkModelExists(string &$model, bool $qualifyModel = true, bool $disablePackageNamespaceTemporarily = false): bool
    {
        if ($disablePackageNamespaceTemporarily) {
            $this->is_package_namespace_disabled = true;
        }

        if ($qualifyModel) {
            $model = $this->qualifyModel($model);
        }
        else {
            $model = (string) $this->cleanClassNamespace($model);
        }

        $this->is_package_namespace_disabled = false;

        return $this->model_exists = is_eloquent_model($model);
    }
}
