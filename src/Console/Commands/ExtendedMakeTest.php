<?php


namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Traits\UsesVendorPackageInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ExtendedMakeResource
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-10
 */
class ExtendedMakeTest extends TestMakeCommand
{
    use UsesVendorPackageInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test file using custom stub.';

    /**
     * @var bool
     */
    protected bool $model_exists = false;

    protected bool $skip_model_check = false;

    /**
     * @var string|null
     */
    protected ?string $model_class = null;

    /**
     * @var string|null
     */
    protected ?string $model_snake = null;

    /**
     * @var string|null
     */
    protected ?string $model_kebab = null;

    /***** OVERRIDDEN FUNCTIONS *****/

    /**
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        // Initiate Stuff

        $this->setVendorAndPackage($this);

        $this->setOtherFields();

        $this->info('Model Exists: ' . ($this->model_exists ? 'true' : 'false'));
        $this->info('Skipping Model Check: ' . ($this->skip_model_check ? 'true' : 'false'));
        $this->info('Model Class: ' . $this->getModelClass());
        $this->info('Model Kebab: ' . $this->getModelKebab());
        $this->info('Model Snake: ' . $this->getModelSnake());

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $stub = '/../../../stubs/test' . ($this->option('unit') ? '.unit' : null). ($this->option('model') ? '.model' : null) . '.custom.stub';

        if (File::exists($path = __DIR__ . $stub) === FALSE) {
            return parent::getStub();
        }

        return $path;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            parent::getOptions(),
            $this->getDefaultPackageOptions(),
            [
                ['model', 'm', InputOption::VALUE_REQUIRED, 'Generate a test file for the given model.'],
                ['skip', 's', InputOption::VALUE_NONE, 'Skip model existence check. Use if Laravel fails to verify a legitimate Eloquent Model class.']
            ],
        );
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

        $path = $this->package_dir ? package_test_path($this->package_dir).DIRECTORY_SEPARATOR : base_path('tests');

        return $path.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceOtherStuff($stub)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceOtherStuff(string &$stub): static
    {
        if (! $this->model_exists) {
            return $this;
        }

        $searches = [
            ['{{ model_class }}', '{{ model_kebab }}', '{{ model_snake }}'],
            ['{{model_class}}', '{{model_kebab}}', '{{model_snake}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getModelClass(), $this->getModelKebab(), $this->getModelSnake()],
                $stub
            );
        }

        return $this;
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @return void
     */
    public function setOtherFields(): void
    {
        $this->skip_model_check = $this->option('skip');

        if ($this->hasOption('model') && $model = $this->option('model')) {
            $model_copy = $model;
            if ($this->model_exists = (is_eloquent_model($model) || is_eloquent_model($model = $this->package_namespace . 'Models' . '\\' . $model) || $this->skip_model_check)) {
                $this->setModelClass($model);
                $this->setModelKebab($model_copy);
                $this->setModelSnake($model_copy);
            }
        }
    }

    /**
     * @param string|null $model_class
     */
    public function setModelClass(?string $model_class): void
    {
        $this->model_class = $model_class;
    }

    /**
     * @return string|null
     */
    public function getModelClass(): ?string
    {
        return $this->model_class;
    }

    /**
     * @param string|null $str
     */
    public function setModelSnake(?string $str): void
    {
        $this->model_snake = Str::snake($str);
    }

    /**
     * @return string|null
     */
    public function getModelSnake(): ?string
    {
        return $this->model_snake;
    }

    /**
     * @param string|null $str
     */
    public function setModelKebab(?string $str): void
    {
        $this->model_kebab = Str::kebab($str);
    }

    /**
     * @return string|null
     */
    public function getModelKebab(): ?string
    {
        return $this->model_kebab;
    }
}
