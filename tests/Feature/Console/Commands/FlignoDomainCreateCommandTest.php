<?php

namespace Fligno\BoilerplateGenerator\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class FlignoDomainCreateCommandTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class FlignoDomainCreateCommandTest extends TestCase
{
    /**
     * Example Test
     *
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
