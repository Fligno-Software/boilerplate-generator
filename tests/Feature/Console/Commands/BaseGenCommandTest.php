<?php

namespace Fligno\BoilerplateGenerator\Feature\Console\Commands;

use Tests\TestCase;

abstract class BaseGenCommandTest extends TestCase
{
    public function beforeGenTest() {
        echo 'James';
    }

    public function afterGenTest() {
        echo 'Carlo';
    }
}
