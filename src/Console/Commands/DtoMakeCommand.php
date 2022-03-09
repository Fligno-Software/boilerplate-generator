<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class DtoMakeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-25
 */
class DtoMakeCommand extends GeneratorCommand
{
    use UsesCommandVendorPackageDomainTrait;

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
     * @throws FileNotFoundException|PackageNotFoundException|MissingNameArgumentException
     */
    public function handle(): ?bool
    {
        $this->setVendorPackageDomain();

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

        return starterKit()->clearCache();
    }

    /**
     * @param string $dtoType
     */
    protected function setDtoType(string $dtoType): void
    {
        $this->dtoType = $dtoType;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        $nameInput = trim($this->argument('name'));

        if ($this->dtoType === 'request') {
            return 'DataTransferObjects/' . $nameInput . 'RequestData';
        }

        return 'DataTransferObjects/' . $nameInput . 'ResponseData';
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

        $path = $this->getPackageDomainFullPath();

        return $path.'/'.str_replace('\\', '/', $name).'.php';
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
        return __DIR__ . '/../../../stubs/dto.' . $this->dtoType . '.custom.stub';
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
