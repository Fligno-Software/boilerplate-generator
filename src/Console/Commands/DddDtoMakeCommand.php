<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesVendorPackage;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class DddDtoMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-25
 */
class DddDtoMakeCommand extends GeneratorCommand
{
    use UsesVendorPackage;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:dto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create data tranfer object (DTO) files in Laravel or in a specific package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Data Transfer Object';

    /**
     * @var string
     */
    protected string $dtoType = 'request';

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

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @throws FileNotFoundException|PackageNotFoundException|JsonException|MissingNameArgumentException
     */
    public function handle()
    {
        $this->setVendorAndPackage();

        if ($this->option('request') === FALSE && $this->option('response') === FALSE) {
            $this->input->setOption('request', true);
            $this->input->setOption('response', true);
        }

        if ($this->option('request')) {
            $this->setDtoType('request');
            parent::handle();
        }

        if ($this->option('response')) {
            $this->setDtoType('response');
            parent::handle();
        }
    }

    /**
     * @param string $dtoType
     */
    protected function setDtoType(string $dtoType): void
    {
        $this->dtoType = $dtoType;
    }

    /**
     * @return string
     */
    public function getDtoType(): string
    {
        return $this->dtoType;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        $nameInput = trim($this->argument('name'));

        if ($this->getDtoType() === 'request') {
            return 'DataTransferObjects' . DIRECTORY_SEPARATOR . $nameInput . 'RequestData';
        }

        return 'DataTransferObjects' . DIRECTORY_SEPARATOR . $nameInput . 'ResponseData';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $path = $this->package_dir ? package_app_path($this->package_dir) : app_path();

        return $path.DIRECTORY_SEPARATOR.str_replace('\\', '/', $name).'.php';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['request', null, InputOption::VALUE_NONE, 'Generate request data transfer object (DTO).'],
            ['response', null, InputOption::VALUE_NONE, 'Generate response data transfer object (DTO).'],
        ];
    }

    /**
     * @return string
     */
    #[Pure]
    protected function getStub(): string
    {
        $defaultStub = __DIR__ . '/../../../stubs/ddd.dto.request.custom.stub';

        if (file_exists($temp = __DIR__ . '/../../../stubs/dto.' . $this->getDtoType() . '.custom.stub')) {
            return $temp;
        }

        return $defaultStub;
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return 'Data';
    }
}
