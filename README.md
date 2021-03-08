# Handle static form submissions and other static site things in Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/reecem/static-form.svg?style=flat-square)](https://packagist.org/packages/reecem/static-form)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/reecem/static-form/run-tests?label=tests)](https://github.com/reecem/static-form/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/reecem/static-form.svg?style=flat-square)](https://packagist.org/packages/reecem/static-form)

[![](https://banners.beyondco.de/Static%20Form.png?theme=dark&packageManager=composer+require&packageName=reecem%2Fstatic-form&pattern=xEquals&style=style_2&description=Handle+Static+Site+forms+submissions+inside+your+Laravel+App&md=1&showWatermark=1&fontSize=100px&images=mail&widths=700&heights=400)](https://github.com/reecem/static-from#readme)

Handle Static form submissions inside your Laravel app from Next.JS and Netlify or any other static site server.

## Installation

You can install the package via composer:

```bash
composer require reecem/static-form
```

To install the application you can do the following:

```bash
php artisan static-form:install --provider --config
```

This will install the config file and the service provider, you can though opt for each one separately or use the following:

You can publish the config file with:
```bash
php artisan vendor:publish --provider="ReeceM\StaticForm\StaticFormServiceProvider" --tag="static-form-config"
```

You can publish the Service Provider file with:
```bash
php artisan vendor:publish --provider="ReeceM\StaticForm\StaticFormServiceProvider" --tag="static-form-provider"
```

<!-- You can publish and run the migrations with:
```bash
php artisan vendor:publish --provider="ReeceM\StaticForm\StaticFormServiceProvider" --tag="static-form-migrations"
php artisan migrate
``` -->

<!-- This is the contents of the published config file:

```php
return [
];
``` -->

## Usage

The first step is to generate your token, to do that you can use the console command:

```bash
php artisan static-form --refresh
```

This will generate your token, it will show you the plain text version only during that session.

The other way is to call the API endpoint to generate a new one. The API is secured via the Gate that is defined in the `App\Providers\StaticFormServiceProvider::class`

You can define any logic in there that would allow only authorized people to access the application.

To call the API endpoint, for now you can make a request to the endpoint through a custom UI and javascript code.

| Method      | Endpoint    | Description |
|:----------- | ----------- | ----------- |
| GET         | domain.tld/api/static-form/token | This will return a 200 status and the static-form package version if the toke is found |
| POST|PATCH  | domain.tld/api/static-form/token | A `POST` or `PATCH` request to this endpoint will create a new token |

The url part that says `static-form` can be changed and comes from the config key `static-form.path`

The response for creating a token would be the following JSON with a 201 status:

```json
{
    "plain_token": "random_string_that_is_40_characters_long",
    "message": "Token Created, please keep this as it is available once"
}
```

- [ ] Make a plugin UI, just deciding on if it should be in package or a separate snippet.

## Testing

Testing is currently a work in progress, there are some :), I am manually testing it in an actual application to make sure it works though.

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ReeceM](https://github.com/ReeceM)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
