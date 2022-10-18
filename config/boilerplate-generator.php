<?php

return [
    'pest_enabled' => env('BG_PEST_ENABLED', true),
    'author' => [
        'name' => env('BG_AUTHOR_NAME', 'James Carlo Luchavez'),
        'email' => env('BG_AUTHOR_EMAIL', 'jamescarlo.luchavez@fligno.com'),
        'homepage' => env('BG_AUTHOR_HOMEPAGE', 'https://www.linkedin.com/in/jsluchavez'),
    ],
    'skeleton' => env('BG_PACKAGE_SKELETON', 'https://github.com/Fligno-Software/package-skeleton/archive/develop.zip'),
];
