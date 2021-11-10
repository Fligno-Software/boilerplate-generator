<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Support\Facades\File;

/**
 * Class ExtendedMakeResource
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-09
 */
class ExtendedMakeResource extends ResourceMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class for Eloquent model.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/resource.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
    }
}
