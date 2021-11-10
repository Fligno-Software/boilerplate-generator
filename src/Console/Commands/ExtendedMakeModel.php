<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Facades\File;

/**
 * Class ExtendedMakeModel
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeModel extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model using custom stub.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/model.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === false) {
            return parent::getStub();
        }

        return $path;
    }
}
