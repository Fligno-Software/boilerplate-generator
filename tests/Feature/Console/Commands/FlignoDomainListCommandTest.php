<?php

namespace Fligno\BoilerplateGenerator\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class FlignoDomainListCommandTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class FlignoDomainListCommandTest extends TestCase
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
