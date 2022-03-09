<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ExtendedMakeController
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-20
 */
class ExtendedMakeController extends ControllerMakeCommand
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class in Laravel or in a specific package.';

    /**
     * Controller Commands
     *
     * @var array|string[]
     */
    public array $controllerMethods = [
        'Index' => 'Collected',
        'Store' => 'Created',
        'Show' => 'Shown',
        'Update' => 'Updated',
        'Delete' => 'Archived'
    ];

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addPackageOptions();
    }

    /**
     * @param bool $isModelRestorable
     * @return Collection
     */
    public function getControllerMethods(bool $isModelRestorable = true): Collection
    {
        $collection = collect($this->controllerMethods);

        if ($isModelRestorable) {
            return $collection->put('Restore', 'Restored');
        }

        return $collection;
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

        return parent::handle() && starterKit()->clearCache();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = null;

        if ($type = $this->option('type')) {
            $stub = __DIR__ . "/stubs/controller.{$type}.stub";
        } elseif ($this->option('parent')) {
            $stub = __DIR__ . '/../../../stubs/controller.nested.custom.stub';
        } elseif ($this->option('model')) {
            $stub = __DIR__ . '/../../../stubs/controller.model.custom.stub';
        } elseif ($this->option('invokable')) {
            $stub = __DIR__ . '/../../../stubs/controller.invokable.custom.stub';
        } elseif ($this->option('resource')) {
            $stub = __DIR__ . '/../../../stubs/controller.custom.stub';
        }

        if ($this->option('api')) {
            if (is_null($stub)) {
                $stub = __DIR__ . '/../../../stubs/controller.api.custom.stub';
            }
            elseif (! $this->option('invokable')) {
                $stub = str_replace('.custom.stub', '.api.custom.stub', $stub);
            }
        }

        $stub = $stub ?? __DIR__ . '/../../../stubs/controller.plain.custom.stub';

        if (file_exists($stub) === FALSE) {
            return parent::getStub();
        }

        return $stub;
    }

    /**
     * @param string $option
     * @return string
     */
    protected function getModelClass(string $option): string
    {
        $modelClass = $this->parseModel($this->option($option));

        if (! class_exists($modelClass)) {
            if ($this->confirm("{$modelClass} model does not exist. Do you want to generate it?", true)) {
                $args = $this->getPackageArgs();
                $args['name'] = $modelClass;

                $this->call('gen:model', $args);
            }
            else {
                $alternativeModels = collect();

                if (($packageDomainFullPath = $this->getPackageDomainFullPath()) !== app_path()) {
                    if (file_exists($temp = $packageDomainFullPath . '/Models')) {
                        $alternativeModels = $alternativeModels->merge(collect_classes_from_path($temp)?->values());
                    }

                    if ($this->package_dir && ($temp = package_app_path($this->package_dir)) && $temp !== $packageDomainFullPath && file_exists($temp .= '/Models')) {
                        $alternativeModels = $alternativeModels->merge(collect_classes_from_path($temp)?->values());
                    }
                }

                $alternativeModels = $alternativeModels->merge(collect_classes_from_path(app_path('Models'))?->values());

                $defaultAlternativeModel = 'none';

                $modelClass = $this->choice('Choose alternative ' . ($option === 'parent' ? $option . ' ' : null) . 'model', $alternativeModels->prepend($defaultAlternativeModel)->toArray(), 0);

                $modelClass = $modelClass === $defaultAlternativeModel ? null : $modelClass;

                $this->input->setOption($option, $modelClass);
            }
        }

        return $modelClass;
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    #[ArrayShape(['ParentDummyFullModelClass' => "mixed|string", '{{ namespacedParentModel }}' => "mixed|string", '{{namespacedParentModel}}' => "mixed|string", 'ParentDummyModelClass' => "string", '{{ parentModel }}' => "string", '{{parentModel}}' => "string", 'ParentDummyModelVariable' => "string", '{{ parentModelVariable }}' => "string", '{{parentModelVariable}}' => "string"])]
    protected function buildParentReplacements(): array
    {
        $parentModelClass = $this->getModelClass('parent');

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            '{{ namespacedParentModel }}' => $parentModelClass,
            '{{namespacedParentModel}}' => $parentModelClass,
            'ParentDummyModelClass' => class_basename($parentModelClass),
            '{{ parentModel }}' => class_basename($parentModelClass),
            '{{parentModel}}' => class_basename($parentModelClass),
            'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)),
            '{{ parentModelVariable }}' => lcfirst(class_basename($parentModelClass)),
            '{{parentModelVariable}}' => lcfirst(class_basename($parentModelClass)),
        ];
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->getModelClass('model');

        $replaceModelNamespaces = [];

        if ($this->option('model')) {
            $replace = $this->buildFormRequestReplacements($replace, $modelClass);
            $replaceModelNamespaces = [
                'DummyFullModelClass' => $modelClass,
                '{{ namespacedModel }}' => $modelClass,
                '{{namespacedModel}}' => $modelClass,
                'DummyModelClass' => class_basename($modelClass),
                '{{ model }}' => class_basename($modelClass),
                '{{model}}' => class_basename($modelClass),
                'DummyModelVariable' => lcfirst(class_basename($modelClass)),
                '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
                '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
            ];
        }

        return array_merge($replace, $replaceModelNamespaces);
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     * @param string $modelClass
     * @return array
     */
    protected function buildFormRequestReplacements(array $replace, $modelClass): array
    {
        if ($modelClass) {

            $result = collect();

            $model = Str::of($modelClass)->afterLast('\\');
            $this->getControllerMethods()->each(function ($event, $request) use ($model, $result) {
                // Generate Request
                $requestClass = $request . $model . 'Request';
                $requestClassPath = $model . '\\' . $requestClass;
                $namespacedRequestClass = $this->rootNamespace() . 'Http\\Requests\\'. $requestClassPath;

                $requestArgs = $this->getPackageArgs();
                $requestArgs['name'] = $requestClassPath;
                $this->call('gen:request', $requestArgs);

                $result->put('{{ ' . Str::camel($request . 'Request') . ' }}', $requestClass);
                $result->put('{{' . Str::camel($request . 'Request') . '}}', $requestClass);
                $result->put('{{ ' . Str::camel('namespaced'. $request . 'Request') . ' }}', $namespacedRequestClass);
                $result->put('{{' . Str::camel('namespaced'. $request . 'Request') . '}}', $namespacedRequestClass);

                // Generate Event
                $eventClass = $model . $event . 'Event';
                $eventClassPath = $model . '\\' . $eventClass;
                $namespacedEventClass = $this->rootNamespace() . 'Events\\'. $eventClassPath;

                $eventArgs = $this->getPackageArgs();
                $eventArgs['name'] = $eventClassPath;
                $this->call('gen:event', $eventArgs);

                $result->put('{{ ' . Str::camel($request . 'Event') . ' }}', $eventClass);
                $result->put('{{' . Str::camel($request . 'Event') . '}}', $eventClass);
                $result->put('{{ ' . Str::camel('namespaced'. $request . 'Event') . ' }}', $namespacedEventClass);
                $result->put('{{' . Str::camel('namespaced'. $request . 'Event') . '}}', $namespacedEventClass);
            });

            return array_merge($replace, $result->toArray());
        }

        return [];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        $options = collect(parent::getOptions())->filter(fn($value) => ! collect($value)->contains('requests'))->toArray();

        return array_merge(
            $options,
            [
                ['repo', null, InputOption::VALUE_NONE, 'Create new repository class based on the model.'],
                ['skip-model', null, InputOption::VALUE_NONE, 'Proceed as if model is already created.'],
            ]
        );
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Controller';
    }
}
