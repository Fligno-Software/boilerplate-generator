<?php

use function Pest\Laravel\artisan;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

//dataset('commands', function () {
//    return ['command A', 'command B'];
//});
//
//it('has something', function () {
//    assertTrue(true);
//})->with('commands');
//
//it('is equal to my email', function ($email) {
//    assertEquals('jamescarlo.luchavez@fligno.com', $email, 'Oh no...');
//})->with('emails');

dataset('packages', [
    'dummy package 1' => 'dummy/test-one',
//    'dummy package 2' => 'dummy/test-two',
//    'dummy package 3' => 'dummy/test-three',
]);

it('can create new package', function ($package) {
    artisan('bg:package:create', ['package' => $package])
//        ->expectsQuestion('What will be the vendor name?', false)
//        ->expectsQuestion('What will be the package name?', false)
//        ->expectsQuestion('What package skeleton would you like to use?', false)
//        ->expectsQuestion('Who is the author?', false)
//        ->expectsQuestion("What is the author's e-mail?", false)
//        ->expectsQuestion("What is the author's website?", false)
//        ->expectsQuestion('How would you describe the package?', false)
//        ->expectsQuestion('Under which license will it be released?', false)
    ;
    expect(true)->toBe(true);
})->with('packages');

it('can delete a package', function ($package) {
    artisan('bg:package:remove', ['package' => $package])
        ->expectsConfirmation('Do you want to remove the vendor directory?', 'yes');
})->with('packages');



