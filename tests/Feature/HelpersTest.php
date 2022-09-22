<?php

uses()->group('helpers');

it('can parse domain to path', function (string $domain) {
    $str = null;
    foreach (explode('.', $domain) as $d) {
        $str .= '/domains/'.$d;
    }
    expect(parse_domain($domain))->toBe($str);
})->with('domains');

it('can parse domain to namespace', function (string $domain) {
    $str = null;
    foreach (explode('.', $domain) as $d) {
        $str .= '\\Domains\\'.$d;
    }
    expect(parse_domain($domain, true))->toBe($str);
})->with('domains');

/***** PATHS *****/

it('can create package-domain base path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html',
    ],
])->group('base', 'path');

it('can create package-domain app path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_app_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/src',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/src',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/src',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/app',
    ],
])->group('app', 'path');

it('can create package-domain database path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_database_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/database',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/database',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/database',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/database',
    ],
])->group('database', 'path');

it('can create package-domain migrations path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_migrations_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/database/migrations',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/database/migrations',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/database/migrations',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/database/migrations',
    ],
])->group('migrations', 'path');

it('can create package-domain seeders path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_seeders_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/database/seeders',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/database/seeders',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/database/seeders',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/database/seeders',
    ],
])->group('seeders', 'path');

it('can create package-domain factories path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_factories_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/database/factories',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/database/factories',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/database/factories',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/database/factories',
    ],
])->group('factories', 'path');

it('can create package-domain resources path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_resources_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/resources',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/resources',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/resources',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/resources',
    ],
])->group('resources', 'path');

it('can create package-domain views path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_views_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/resources/views',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/resources/views',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/resources/views',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/resources/views',
    ],
])->group('views', 'path');

it('can create package-domain lang path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_lang_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/resources/lang',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/resources/lang',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/resources/lang',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/resources/lang',
    ],
])->group('lang', 'path');

it('can create package-domain tests path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_tests_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/tests',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/tests',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/tests',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/tests',
    ],
])->group('tests', 'path');

it('can create package-domain routes path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_routes_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/routes',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/routes',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/routes',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/routes',
    ],
])->group('routes', 'path');

it('can create package-domain helpers path', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_helpers_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/helpers',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/helpers',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/helpers',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/helpers',
    ],
])->group('helpers', 'path');

/***** NAMESPACES *****/

it('can create package-domain base namespace', function (string|null $package, string|null $domain, string|null $expected) {
    expect(package_domain_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => null,
    ],
])->group('base', 'namespace');

it('can create package-domain app namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_app_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World\\',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage\\',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World\\',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => 'App\\',
    ],
])->group('app', 'namespace');

it('can create package-domain database namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_database_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World\\Database',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage\\Database',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World\\Database',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => 'Database',
    ],
])->group('database', 'namespace');

it('can create package-domain seeders namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_seeders_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World\\Database\\Seeders\\',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage\\Database\\Seeders\\',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World\\Database\\Seeders\\',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => 'Database\\Seeders\\',
    ],
]);

it('can create package-domain factories namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_factories_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World\\Database\\Factories\\',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage\\Database\\Factories\\',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World\\Database\\Factories\\',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => 'Database\\Factories\\',
    ],
]);

it('can create package-domain tests namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_tests_namespace($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => 'Fligno\\TestPackage\\Domains\\Hello\\Domains\\World\\Tests\\',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => 'Fligno\\TestPackage\\Tests\\',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => 'Domains\\Hello\\Domains\\World\\Tests\\',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => 'Tests\\',
    ],
]);

it('can create package-domain routes namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_routes_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/routes',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/routes',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/routes',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/routes',
    ],
]);

it('can create package-domain helpers namespace', function (string|null $package, string|null $domain, string $expected) {
    expect(package_domain_helpers_path($package, $domain, true))->toBe($expected);
})->with([
    'both package and domain' => [
        'package' => 'fligno/test-package',
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/packages/fligno/test-package/domains/Hello/domains/World/helpers',
    ],
    'package only' => [
        'package' => 'fligno/test-package',
        'domain' => null,
        'expected' => '/var/www/html/packages/fligno/test-package/helpers',
    ],
    'domain only' => [
        'package' => null,
        'domain' => 'Hello.World',
        'expected' => '/var/www/html/domains/Hello/domains/World/helpers',
    ],
    'no package and no domain' => [
        'package' => null,
        'domain' => null,
        'expected' => '/var/www/html/helpers',
    ],
]);

/***** COMPOSER JSON *****/

it('can get contents of composer.json at base path', function () {
    $contents = getContentsFromComposerJson();
    expect($contents)
        ->toBeIterable()
        ->toMatchArray([
            'name' => 'laravel/laravel',
            'type' => 'project',
            'description' => 'The Laravel Framework.',
        ]);
});

it('can set contents of composer.json at base path')
    ->skip('Skipped because it can cause Composer issues');
