<?php

namespace Fligno\BoilerplateGenerator\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class HelperMakeCommandTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class HelperMakeCommandTest extends BaseGenCommandTest
{
    /**
     * Example Test
     *
     * @test
     */
    public function example(): void
    {
        $this->beforeGenTest();

        $response = $this->get('/');

        $response->assertStatus(200);

        $this->afterGenTest();
    }
}
