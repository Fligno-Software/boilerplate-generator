<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ExtendedMakeController
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeController extends ControllerMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'gen:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API controller file for a model using custom stub.';

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions(),
            [
                ['yes', 'y', InputOption::VALUE_NONE, 'Yes to all generate questions.'],
                ['requestsFolder', null, InputOption::VALUE_OPTIONAL, 'Target request folder.'],
            ]
        );
    }

    /**
     * @var bool
     */
    protected bool $yes_to_questions = FALSE;

    /**
     * @var bool
     */
    protected bool $skip_all_questions = FALSE;

    /**
     * @var bool
     */
    protected bool $is_ddd = FALSE;

    public function getStub(): string
    {
        $stub = $this->option('ddd') ? '/../../../stubs/controller.invokable.stub' : '/../../../stubs/controller.model.api.custom.stub';

        if (file_exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
    }

    /***** GETTERS & SETTERS *****/

    /**
     * @return string|null
     */
    public function getModelName(): ?string
    {
        return $this->option('model') ?? $this->extractModelFromName();
    }

    public function getModelClass(): string
    {
        return $this->parseModel($this->getModelName());
    }

    /***** METHODS *****/

    public function extractModelFromName(): string
    {
        $name = $this->getNameInput();
        $name = Str::snake($name);
        $name = Str::before($name, 'controller');
        return Str::studly($name);
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->skip_all_questions = $this->hasOption('no-interaction');
        $this->is_ddd = $this->hasOption('ddd');
        $this->setVendorAndPackage($this);

        return parent::handle();
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->getModelClass();

        if (! $this->skip_all_questions && ! $this->is_ddd && class_exists($modelClass) === FALSE) {

            $this->yes_to_questions = $this->hasOption('y') && $this->hasOption('ddd');

            $args = [];
            $will_generate = FALSE;

            if($this->yes_to_questions || $this->confirm("{$modelClass} model does not exist. Do you want to generate it?", true)) {
                $args['name'] = $modelClass;
                $will_generate = TRUE;
            }

            if ($this->yes_to_questions || $this->confirm("{$modelClass} model has no migration file yet. Do you want to generate it?", true)) {
                $args['-m'] = TRUE;
            }

            if ($will_generate) {
                $this->call('gen:model', $args);
            }
        }

        return array_merge($replace, [
            '{{ requestFolder }}' => $this->option('requestsFolder'),
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
}
