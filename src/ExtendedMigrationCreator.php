<?php

namespace Fligno\BoilerplateGenerator;

use Exception;
use \Illuminate\Database\Migrations\MigrationCreator;

class ExtendedMigrationCreator extends MigrationCreator
{
    /**
     * @var string|null
     */
    protected ?string $package_path = null;

    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string|null  $table
     * @param  bool  $create
     * @return string
     *
     * @throws Exception
     */
    public function create($name, $path, $table = null, $create = false): string
    {
        $path = $this->package_path ? package_migration_path($this->package_path) : $path;

        return parent::create($name, $path, $table, $create);
    }

    /***** SETTER & GETTER *****/

    /**
     * @param string|null $package_path
     */
    public function setPackagePath(?string $package_path): void
    {
        $this->package_path = $package_path;
    }
}
