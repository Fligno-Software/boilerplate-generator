<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FlignoStarter
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class FlignoStarter extends Command
{
    use UsesVendorPackage;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:start';

    /**
     * @var string
     */
    protected string $model_name;

    /**
     * @var string
     */
    protected string $model_class;

    /**
     * @var bool
     */
    protected bool $yes_to_questions = FALSE;

    /**
     * @var bool
     */
    protected bool $is_model_created = FALSE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model with migration, API controller, request, event, and resource files.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageOptions();
    }

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->setVendorAndPackage($this);

        $this->startPreparations();

        $this->startMagicShow();
    }

    /***** GETTERS & SETTERS *****/

    /**
     * @return string|null
     */
    public function getModelName(): ?string
    {
        return $this->model_name;
    }

    /**
     * @return string|null
     */
    public function getModelClass(): ?string
    {
        return $this->model_class;
    }

    public function setFields(string $model): void
    {
        $this->model_name = Str::studly(Str::singular($model));
        $this->model_class = $this->parseModel($this->model_name);
    }

    /***** OTHER METHODS *****/

    public function startPreparations(): void
    {
        $this->yes_to_questions = $this->option('yes');

        if ($model_name = $this->argument('model')) {
            $this->setFields($model_name);
        }
    }

    public function modelExists(): bool
    {
        if ($this->getModelClass()) {
            return class_exists($this->getModelClass());
        }

        $this->startPreparations();

        return $this->modelExists();
    }

    public function startMagicShow(): void
    {
        // Create Model
        $this->generateModel();

        // Create Resource File
        $this->generateResource();

        // Create Tests
//        $this->generateTests();
    }

    /***** COMMANDS *****/

    /**
     * This will auto-generate a model class.
     */
    protected function generateModel(): void
    {
        if ($this->modelExists() === FALSE) {
            $modelClass = $this->getModelClass();
            $args = $this->getPackageArgs();
            $will_generate = FALSE;

            if($this->yes_to_questions || $this->confirm("{$modelClass} model does not exist. Do you want to generate it?", true)) {
                $args['name'] = $this->model_name;
                $args['--all'] = true;
                $args['--repo'] = true;
                $args['--api'] = true;
                $will_generate = TRUE;
            }

            if ($will_generate) {
                $this->call('gen:model', $args);
                $this->is_model_created = TRUE;
            }
        }
        else {
            $this->error('Model already exists!');
            $this->is_model_created = TRUE;
        }
    }

    /**
     * This will auto-generate a resource class for the model.
     */
    public function generateResource(): void
    {
        if($this->is_model_created && ($this->yes_to_questions || $this->confirm("Do you want to generate RESOURCE file?", true))) {
            $args = $this->getPackageArgs();
            $args['name'] = $this->getModelName() . 'Resource';
            $this->call('gen:resource', $args);
        }
    }

    /**
     * This will auto-generate a test for each of the functions that you have on the generated controller class.
     */
    public function generateTests(): void
    {
        if ($this->is_model_created && ($this->yes_to_questions || $this->confirm("Do you want to generate a test file?", true))) {
            $model = $this->getModelName();
            $folder = $this->option('requestsFolder');

            $args = $this->getPackageArgs();
            $args['name'] = $folder . $model . '/' . $model . 'Test';
            $args['--model'] = $model;
            $args['--skip'] = true;

            $this->call('gen:test', $args);
        }
    }

    /***** MODEL VALIDATION *****/

    /**
     * Get the fully-qualified model class name.
     *
     * @param string $model
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function parseModel(string $model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel(string $model): string
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models'))
            ? $rootNamespace.'Models\\'.$model
            : $rootNamespace.$model;
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the model to create.'],
        ];
    }

    /**
     * @return array
     */
    #[Pure] protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            [
                ['yes', 'y', InputOption::VALUE_NONE, 'Yes to all generate questions.']
            ]
        );
    }
}
