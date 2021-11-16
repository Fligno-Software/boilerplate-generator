<?php

namespace Fligno\BoilerplateGenerator;

use \Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;

class CustomMigrationCreator extends MigrationCreator
{

    /**
     * @param Filesystem $files
     * @param string|null $customStubPath
     */
    public function __construct(Filesystem $files, $customStubPath = '')
    {
        info('From CustomMigrationCreator');

        parent::__construct($files, $customStubPath);
    }
}
