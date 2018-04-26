# Introduction

Main goal of this project is to provide a standalone database (SQLite3) that references
all functions, constants, classes, interfaces on PHP standard distribution and about 100 extensions.

This database is currently only supported by its initial project php-compatinfo on version 5.0

# Features

* a Symfony console application to handle data (json files) of the SQLite3 database (see `data/handleDB.php` script)

More than 100 extensions (PHP standard distribution, but also PECL) are currently supported :

* reference all functions
* reference all constants
* reference all classes
* reference all classes constants
* reference all interfaces
* reference all methods
* reference all ini entries
* reference all releases

Version 1.32.0 support informations to latest PHP versions :

* PHP 5.2.17
* PHP 5.3.29
* PHP 5.4.45
* PHP 5.5.38
* PHP 5.6.36
* PHP 7.0.30
* PHP 7.1.17

Currently 106 extensions are referenced in the database.

For future versions, see the `CHANGELOG` file.

# Example

See `examples/useExtensionFactory.php` script to learn how to access to informations in database.

# Requirements

PHP 5.4 or greater

# Unit Tests

Each extension supported has its own Test case file.
If you launch all tests, depending of your platform (CPU, memory), you may have sensation
that PHPUnit do nothing for a long time.

Reason is PHPUnit count all tests before running them. Sebastian Bergmann has opened
a [ticket](https://github.com/sebastianbergmann/phpunit/issues/10) to solve this situation.

Alternative to this issue, is to used the Phing PHPUnitTask. This is really possible now PHPUnit
provide a library-only PHAR (see ticket [#1925](https://github.com/sebastianbergmann/phpunit/issues/1925)).

Download the PHPUnit PHAR library at https://phar.phpunit.de/phpunit-library.phar

# Authors

* Laurent Laville
* Remi Collet (contributor on many extensions and unit tests)

# License

This handler is licensed under the BSD-3-clauses License - see the `LICENSE` file for details
