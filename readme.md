# Fligno Packager & Boilerplate Generator for Laravel

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

Note: If you haven't setup your Gitlab Personal Access Token for Composer yet, please follow this [instruction](setup-gitlab-pat.md).

``` bash
$ composer config repositories.git.fligno.com/440 '{"type": "composer", "url": "https://git.fligno.com/api/v4/group/440/-/packages/composer/packages.json"}'
```

```bash
$ composer req fligno/boilerplate-generator:^1.0.0 --dev
```

## Usage

| File Type | Laravel | Fligno | Description | Status |
| --------- | :-----: | :----: | ------ | ----------- |
| Cast | `make:cast` | `gen:cast` | Create a new custom Eloquent cast class in Laravel or in a specific package. | 100% |
| Channel | `make:channel` | `gen:channel` | Create a new channel class in Laravel or in a specific package. | 100% |
| Command | `make:command` | `gen:command` | Create a new Artisan command in Laravel or in a specific package. | 100% |
| Component | `make:component` | `gen:component` | Create a new view component class in Laravel or in a specific package. | 100% |
| Controller | `make:controller` | `gen:controller` | Create a new controller class in Laravel or in a specific package. | 100% |
| Event | `make:event` | `gen:event` | Create a new event class in Laravel or in a specific package. | 100% |
| Exception | `make:exception` | `gen:exception` | Create a new custom exception class in Laravel or in a specific package. | 100% |
| Factory | `make:factory` | `gen:factory` | Create a new model factory in Laravel or in a specific package. | 100% |
| Job | `make:job` | `gen:job` | Create a new job class in Laravel or in a specific package. | 100% |
| Interface |  | `gen:interface` | Create a new interface in Laravel or in a specific package. | 100% |
| Listener | `make:listener` | `gen:listener` | Create a new event listener class in Laravel or in a specific package. | 100% |
| Mail | `make:mail` | `gen:mail` | Create a new email class in Laravel or in a specific package. | 100% |
| Middleware | `make:middleware` | `gen:middleware` | Create a new middleware class in Laravel or in a specific package. | 100% |
| Migration | `make:migration` | `gen:migration` | Create a new migration file in Laravel or in a specific package. | 100% |
| Model | `make:model` | `gen:model` | Create a new Eloquent model class in Laravel or in a specific package. | 100% |
| Notification | `make:notification` | `gen:notification` | Create a new notification class in Laravel or in a specific package. | 100% |
| Observer | `make:observer` | `gen:observer` | Create a new observer class in Laravel or in a specific package. | 100% |
| Package |  | `gen:package` | Create a Laravel package. [Wrapper for `packager:new` of Jeroen-G/laravel-packager] | 100% |
| Policy | `make:policy` | `gen:policy` | Create a new policy class in Laravel or in a specific package. | 100% |
| Provider | `make:provider` | `gen:provider` | Create a new service provider class in Laravel or in a specific package. | 100% |
| Repository |  | `gen:repository` | Create a new repository class in Laravel or in a specific package. | 100% |
| Request | `make:request` | `gen:request` | Create a new form request class in Laravel or in a specific package. | 100% |
| Resource | `make:resource` | `gen:resource` | Create a new resource file in Laravel or in a specific package. | 100% |
| Rule | `make:rule` | `gen:rule` | Create a new validation rule in Laravel or in a specific package. | 100% |
| Seeder | `make:seeder` | `gen:seeder` | Create a new seeder class in Laravel or in a specific package. | 100% |
| Start | | `gen:start` | | 100% |
| Test | `make:test` | `gen:test` | Create a new test class in Laravel or in a specific package. | 100% |
| Trait | | `gen:trait` | Create a new interface in Laravel or in a specific package. | 100% |

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email jamescarlo.luchavez@fligno.com instead of using the issue tracker.

## Credits

- [James Carlo Luchavez][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/fligno/boilerplate-generator.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/fligno/boilerplate-generator.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/fligno/boilerplate-generator/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/fligno/boilerplate-generator
[link-downloads]: https://packagist.org/packages/fligno/boilerplate-generator
[link-travis]: https://travis-ci.org/fligno/boilerplate-generator
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/fligno
[link-contributors]: ../../contributors
