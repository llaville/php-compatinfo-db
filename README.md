
# PHP CompatInfoDB

| Stable | Upcoming |
|:------:|:--------:|
| [![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/php-compatinfo-db)](https://packagist.org/packages/bartlett/php-compatinfo-db) | [![Unstable Version](https://img.shields.io/packagist/vpre/bartlett/php-compatinfo-db)](https://packagist.org/packages/bartlett/php-compatinfo-db) |
| [![Minimum PHP Version)](https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db)](https://php.net/) | [![Minimum PHP Version)](https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/3.x-dev?color=orange)](https://php.net/) |
| [![Branch Master](https://img.shields.io/badge/branch-master-blue)](https://github.com/llaville/php-compatinfo-db) | [![Branch 3.x](https://img.shields.io/badge/branch-3.x-orange)](https://github.com/llaville/php-compatinfo-db/tree/3.x) |

Main goal of this project is to provide a standalone database (SQLite3) that references
all functions, constants, classes, interfaces on PHP standard distribution and about 100 extensions.

This database is currently only supported by its initial project [php-compatinfo](https://github.com/llaville/php-compat-info) on versions 5.x-dev

## Features

* a Symfony console application to handle data (json files) of the SQLite3 database is provided on CLI API

More than 100 extensions (PHP standard distribution, but also PECL) are currently supported :

* reference all functions
* reference all constants
* reference all classes
* reference all classes constants
* reference all interfaces
* reference all methods
* reference all ini entries
* reference all releases

Version 2.18.0 supports following PHP versions :

* PHP 5.2.17
* PHP 5.3.29
* PHP 5.4.45
* PHP 5.5.38
* PHP 5.6.40
* PHP 7.0.33
* PHP 7.1.33
* PHP 7.2.34
* PHP 7.3.23
* PHP 7.4.11

Currently, 108 extensions referenced in the database.

For future versions, see the `CHANGELOG` file.

## Examples

See `examples/useExtensionFactory.php` script to learn how to access to informations in database.

## Requirements

* PHP 7.1 or greater
* PHPUnit 7 or greater (if you want to run unit tests)

## Installation

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/php-compatinfo-db
```

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)

[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/0)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/0)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/1)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/1)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/2)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/2)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/3)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/3)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/4)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/4)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/5)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/5)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/6)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/6)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/7)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/7)

## License

This project is licensed under the BSD-3-Clause License - see the [LICENSE](https://github.com/llaville/php-compatinfo-db/blob/master/LICENSE) file for details
