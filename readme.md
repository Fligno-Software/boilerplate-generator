# Fligno Packager & Boilerplate Generator for Laravel

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

Note: If you haven't set up your Gitlab Personal Access Token for Composer yet, please follow this [instruction](setup-gitlab-pat.md).

``` bash
$ composer config repositories.git.fligno.com/440 '{"type": "composer", "url": "https://git.fligno.com/api/v4/group/440/-/packages/composer/packages.json"}'
```

```bash
$ composer req fligno/boilerplate-generator:^1.0.0 --dev
```

## Usage

### List of `fligno` commands

| Name            | Command                  | Description                                                                        |
|-----------------|--------------------------|------------------------------------------------------------------------------------|
| Package List    | `fligno:package:list`    | List all locally installed packages.                                               |
| Package Create  | `fligno:package:create`  | Create a new Laravel package.                                                      |
| Package Remove  | `fligno:package:remove`  | Remove a Laravel package.                                                          |
| Package Enable  | `fligno:package:enable`  | Enable a Laravel package.                                                          |
| Package Disable | `fligno:package:disable` | Disable a Laravel package.                                                         |
| Package Clone   | `fligno:package:clone`   | Clone a Laravel package using Git.                                                 |
| Package Publish | `fligno:package:publish` | Publish a Laravel package using Git.                                               |
| Test            | `fligno:test`            | Run the application and package tests.                                             |
| Start           | `fligno:start`           | Create a model with migration, API controller, request, event, and resource files. |


### List of `gen` commands

| File Type    |      Command       | Laravel Counterprt  | Description                                                                  |
|--------------|:------------------:|:-------------------:|:-----------------------------------------------------------------------------|
| Cast         |     `gen:cast`     |     `make:cast`     | Create a new custom Eloquent cast class in Laravel or in a specific package. |
| Channel      |   `gen:channel`    |   `make:channel`    | Create a new channel class in Laravel or in a specific package.              |
| Class        |   `gen:channel`    |                     | Create a new PHP class in Laravel or in a specific package.                  |
| Command      |   `gen:command`    |   `make:command`    | Create a new Artisan command in Laravel or in a specific package.            |
| Component    |  `gen:component`   |  `make:component`   | Create a new view component class in Laravel or in a specific package.       |
| Container    |  `gen:container`   |                     | Create a new service container in Laravel or in a specific package.          |
| Controller   |  `gen:controller`  |  `make:controller`  | Create a new controller class in Laravel or in a specific package.           |
| Docs         |     `gen:docs`     |  `make:controller`  | Generate Scribe documentations.                                              |
| Event        |    `gen:event`     |    `make:event`     | Create a new event class in Laravel or in a specific package.                |
| Exception    |  `gen:exception`   |  `make:exception`   | Create a new custom exception class in Laravel or in a specific package.     |
| Facade       |    `gen:facade`    |                     | Create a new facade in Laravel or in a specific package.                     |
| Factory      |   `gen:factory`    |   `make:factory`    | Create a new model factory in Laravel or in a specific package.              |
| Gitlab CI    |    `gen:gitlab`    |                     | Create a Gitlab CI YML file in a specific package.                           |
| Helper       |    `gen:helper`    |                     | Create a new helper file in Laravel or in a specific package.                |
| Interface    |  `gen:interface`   |                     | Create a new interface in Laravel or in a specific package.                  |
| Job          |     `gen:job`      |     `make:job`      | Create a new job class in Laravel or in a specific package.                  |
| Interface    |  `gen:interface`   |                     | Create a new interface in Laravel or in a specific package.                  |
| Listener     |   `gen:listener`   |   `make:listener`   | Create a new event listener class in Laravel or in a specific package.       |
| Mail         |     `gen:mail`     |     `make:mail`     | Create a new email class in Laravel or in a specific package.                |
| Middleware   |  `gen:middleware`  |  `make:middleware`  | Create a new middleware class in Laravel or in a specific package.           |
| Migration    |  `gen:migration`   |  `make:migration`   | Create a new migration file in Laravel or in a specific package.             |
| Model        |    `gen:model`     |    `make:model`     | Create a new Eloquent model class in Laravel or in a specific package.       |
| Notification | `gen:notification` | `make:notification` | Create a new notification class in Laravel or in a specific package.         |
| Observer     |   `gen:observer`   |   `make:observer`   | Create a new observer class in Laravel or in a specific package.             |
| Policy       |    `gen:policy`    |    `make:policy`    | Create a new policy class in Laravel or in a specific package.               |
| Provider     |   `gen:provider`   |   `make:provider`   | Create a new service provider class in Laravel or in a specific package.     |
| Repository   |  `gen:repository`  |                     | Create a new repository class in Laravel or in a specific package.           |
| Request      |   `gen:request`    |   `make:request`    | Create a new form request class in Laravel or in a specific package.         |
| Resource     |   `gen:resource`   |   `make:resource`   | Create a new resource file in Laravel or in a specific package.              |
| Routes       |    `gen:routes`    |                     | Create web and/or api route files in a specific package.                     |
| Rule         |     `gen:rule`     |     `make:rule`     | Create a new validation rule in Laravel or in a specific package.            |
| Seeder       |    `gen:seeder`    |    `make:seeder`    | Create a new seeder class in Laravel or in a specific package.               |
| Test         |     `gen:test`     |     `make:test`     | Create a new test class in Laravel or in a specific package.                 |
| Trait        |    `gen:trait`     |                     | Create a new interface in Laravel or in a specific package.                  |

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

There would be 2 Packages inside your Laravel Project:
```boilerplate-generator``` and ```<dummy package you will create>```.

1. Create a Dummy Package for Testing:
    ```bash 
    $ php artisan fligno:package:create Dummy Package
    ```

2. Proceed to Testing
    ``` bash
    $ php artisan fligno:test
    ```
    or
    ``` bash
    $ php artisan fligno:test -p
    ```

    It would probably be the same as below
    ``` bash
    Choose target package [Laravel]:
    
    [0] Laravel
    [1] dummy/package
    [2] fligno/boilerplate-generator
    ```
    Choose the ```dummy/package``` you created earlier by entering its corresponding number. In this case, ```[1]```.

3. Wait for the Test to Finish. To further verify, your ```dummy/package``` Package should contain "Random"-named Files including ```Class```, ```Event```, ```Route```, etc.

4. Upon successful testing, you can now remove the Dummy Package you create by using
   ``` bash
    $ php artisan fligno:package:remove Dummy Package
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
