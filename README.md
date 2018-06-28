# Deadline Calculator with support for operating hours and holidays

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bzarzuela/deadline-calculator.svg?style=flat-square)](https://packagist.org/packages/bzarzuela/deadline-calculator)
[![Build Status](https://img.shields.io/travis/bzarzuela/deadline-calculator/master.svg?style=flat-square)](https://travis-ci.org/bzarzuela/deadline-calculator)
[![Quality Score](https://img.shields.io/scrutinizer/g/bzarzuela/deadline-calculator.svg?style=flat-square)](https://scrutinizer-ci.com/g/bzarzuela/deadline-calculator)
[![Total Downloads](https://img.shields.io/packagist/dt/bzarzuela/deadline-calculator.svg?style=flat-square)](https://packagist.org/packages/bzarzuela/deadline-calculator)


A very common requirement in our projects is the ability for the CRM to compute for the deadline of a Ticket.
There are a lot of factors that have to be considered when computing deadlines such as weekends, operating hours that vary, etc

By default, the package assumes a 24/7 operation. The recommended usage is to pre-configure the class and bind it 
to the Service Provider of your application. 

## Installation

You can install the package via composer:

```bash
composer require bzarzuela/deadline-calculator
```

## Usage

``` php
$calculator = new Bzarzuela\DeadlineCalculator();
$calculator->startFrom('2018-06-28 12:20:00);
$calculator->tatInDays(3);
echo $calculator->deadline(); // 2018-07-01 12:20:00 
```

It supports bypassing weekends in the calculation.

``` php
$calculator->noWeekends();
```

TAT can also be set in hours

``` php
$calculator->tatInHours(24);
```

Operating Hours is supported for cases when TAT is measured in hours

``` php
$calculator->tatInHours(24)
    ->operatingHours('09:00:00', '17:00:00'); // 9am to 5pm working hours
```

You can also set or override operating hours on a per-day basis
``` php
$calculator->tatInHours(24)
    ->operatingHours('09:00:00', '17:00:00') // 9am to 5pm working hours
    ->saturday('09:00:00', '12:00:00') // Half day on Saturday
    ->noSunday(); // No work on Sundays
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email bryan@teleserv.ph instead of using the issue tracker.

## Credits

- [Bryan Zarzuela](https://github.com/bzarzuela)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
