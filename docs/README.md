[![Latest Version on Packagist](https://img.shields.io/packagist/v/reecem/static-form.svg?style=flat-square)](https://packagist.org/packages/reecem/static-form)
[![PHPUnit Tests](https://github.com/ReeceM/static-form/actions/workflows/run-tests.yml/badge.svg)](https://github.com/ReeceM/static-form/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/reecem/static-form.svg?style=flat-square)](https://packagist.org/packages/reecem/static-form)
[![Styling](https://github.com/ReeceM/static-form/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/ReeceM/static-form/actions/workflows/php-cs-fixer.yml)

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

You will need to add the following to the `config/app.php` file:

```diff
        /*
         * Package Service Providers...
         */
+        App\Providers\StaticFormServiceProvider::class
```

## Usage

Please see [Usage](usage?id=usage)

## Testing

Testing is currently a work in progress, there are some :), I am manually testing it in an actual application to make sure it works though.

```bash
composer test
```

## Credits

- [ReeceM](https://github.com/ReeceM)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
