<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesCreatesMatchingTest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
    use UsesCreatesMatchingTest;

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
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        return parent::handle();
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
                $stub = '/stubs/controller.api.custom.stub';
            }
            elseif (! $this->option('invokable')) {
                $stub = str_replace('.custom.stub', '.api.custom.stub', $stub);
            }
        }

        $stub = $stub ?? '/stubs/controller.plain.custom.stub';

        if (file_exists($stub) === FALSE) {
            return parent::getStub();
        }

//        $this->info('Controller Stub: ' . $stub);

        return $stub;
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    #[ArrayShape(['ParentDummyFullModelClass' => "mixed|string", '{{ namespacedParentModel }}' => "mixed|string", '{{namespacedParentModel}}' => "mixed|string", 'ParentDummyModelClass' => "string", '{{ parentModel }}' => "string", '{{parentModel}}' => "string", 'ParentDummyModelVariable' => "string", '{{ parentModelVariable }}' => "string", '{{parentModelVariable}}' => "string"])]
    protected function buildParentReplacements(): array
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (!class_exists($parentModelClass) && $this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
            $args = $this->getInitialArgs();
            $args['name'] = $parentModelClass;
            $this->call('gen:model', $args);
        }

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
        $modelClass = $this->parseModel($this->option('model'));

        if (!class_exists($modelClass) && $this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
            $args = $this->getInitialArgs();
            $args['name'] = $modelClass;

            $this->call('gen:model', $args);
        }

        $replace = $this->buildFormRequestReplacements($replace, $modelClass);

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
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
        if ($this->option('requests')) {

            $result = collect();

            $model = Str::of($modelClass)->afterLast('\\');
            $this->getControllerMethods()->each(function ($event, $request) use ($model, $result) {
                // Generate Request
                $requestClass = $request . $model;
                $requestClassPath = $model . '\\' . $request . $model;
                $namespacedRequestClass = $this->package_namespace . 'Http\\Requests\\'. $requestClassPath;

                $requestArgs = $this->getInitialArgs();
                $requestArgs['name'] = $requestClassPath;
                $this->call('gen:request', $requestArgs);

                $result->put('{{ ' . Str::camel($request . 'Request') . ' }}', $requestClass);
                $result->put('{{' . Str::camel($request . 'Request') . '}}', $requestClass);
                $result->put('{{ ' . Str::camel('namespaced'. $request . 'Request') . ' }}', $namespacedRequestClass);
                $result->put('{{' . Str::camel('namespaced'. $request . 'Request') . '}}', $namespacedRequestClass);

                // Generate Event
                $eventClass = $model . $event;
                $eventClassPath = $model . '\\' . $model . $event;
                $namespacedEventClass = $this->package_namespace . 'Events\\'. $eventClassPath;

                $eventArgs = $this->getInitialArgs();
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
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions(false),
            [
                ['repo', null, InputOption::VALUE_NONE, 'Create new repository class based on the model.'],
                ['skip-model', null, InputOption::VALUE_NONE, 'Proceed as if model is already created.'],
            ]
        );
    }
}
