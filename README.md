# Generate invoice's control code according to bolivian laws

[![Latest Version on Packagist](https://img.shields.io/packagist/v/josegus/control-code.svg?style=flat-square)](https://packagist.org/packages/josegus/control-code)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/josegus/control-code/Tests?label=tests)](https://github.com/josegus/control-code/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/josegus/control-code.svg?style=flat-square)](https://packagist.org/packages/josegus/control-code)


This package can generate invoice's control code, according to bolivian laws.

Here's how to use it:

```php
ControlCode::make()
    ->authorizationNumber('29040011007')
    ->invoiceNumber('1503')
    ->customerDocumentNumber('4189179011')
    ->transactionDate('2007-07-02')
    ->transactionMount('2500')
    ->dosificationKey('9rCB7Sv4X29d)5k7N%3ab89p-3(5[A')
    ->generate();
```

## Installation

You can install the package via composer:

```bash
composer require josegus/control-code
```

## Usage

```php
ControlCode::make()
    ->authorizationNumber('29040011007')
    ->invoiceNumber('1503')
    ->customerDocumentNumber('4189179011')
    ->transactionDate('2007-07-02')
    ->transactionMount('2500')
    ->dosificationKey('9rCB7Sv4X29d)5k7N%3ab89p-3(5[A')
    ->generate();
```

Notice that, in order to make it work properly:
- all params must be string
- transaction date must be in one of the following format: "Y-m-d", "Y/m/d", "Ymd"

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email jgvv15@gmail.com instead of using the issue tracker.

## Credits

- [Gustavo Vasquez](https://github.com/josegus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
