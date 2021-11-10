<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Facades\File;

/**
 * Class ExtendedMakeRequest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeRequest extends RequestMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request with authorize as true.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/request.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === false) {
            return parent::getStub();
        }

        return $path;
    }
}
