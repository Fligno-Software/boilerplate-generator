<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class ExtendedMakeController
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 *
 * Usage
 *
 * This generator extends the php artisan make:controller --model={model} --api command
 * It will generate the necessary skeleton to create a CRUD and should contain all the basics for the task.
 *
 * php artisan gen:controller UserController --model=User --requestsFolder=Some\Folder
 * name (UserController) - The name of the controller that you want to generate
 * You can also pass a directory like V2\UserController for your convenience
 *
 * --model (User) - The Eloquent Model to be injected in the controller
 * --requestsFolder (\) - A custom path that you want to use for both Requests and Events file
 *      You must add a backslash on both ends to make this work (e.g. \V2\ or \Admin\)
 */
class ExtendedMakeController extends ControllerMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:controller
    {name : Controller name to be generated}
    {--requestsFolder= : Custom root folder for auto-generated requests}
    {--parent}
    {--model=}
    {--y : Yes to all questions}
    {--skip : Skip all questions}
    ';

    /**
     * @var bool
     */
    protected bool $yes_to_questions = FALSE;

    /**
     * @var bool
     */
    protected bool $skip_all_questions = FALSE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model, controller, request, event, and resource classes automagically!';

    public function getStub(): string
    {
        $stub = '/../../../stubs/controller.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
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


    /**
     * DO NOT TOUCH IF YOU DON'T KNOW WHAT YOU'RE DOING
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->getModelClass();

        $this->yes_to_questions = $this->hasOption('y');

        $this->skip_all_questions = $this->hasOption('skip');

        if (! $this->skip_all_questions && class_exists($modelClass) === FALSE) {

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
