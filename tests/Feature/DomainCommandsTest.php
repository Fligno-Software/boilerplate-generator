<?php

use function Pest\Faker\faker;
use function Pest\Laravel\artisan;
use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertTrue;

uses()->group('domains');

it('can create new domain', function (string $domain) {
    $command = artisan('bg:domain:create', [
        'name' => $domain,
        '--package' => 'none',
        '--no-interaction' => true,
    ]);

    // Explode the domain name to create subdomains
    $domains = explode('.', $domain);

    $slice = array_slice($domains, 0, 1);
    $command->expectsQuestion('Enter new domain name', implode('.', $slice));

    $command->assertSuccessful();
})->with([
    'a 2-level domain' => [
        'domain' => 'Hello.World',
    ]
])
    ->group('create')->only();
