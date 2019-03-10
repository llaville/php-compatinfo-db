[![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/php-compatinfo-db.svg?style=flat-square)](https://packagist.org/packages/bartlett/php-compatinfo-db)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)

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

Version 2.1.2 support informations to latest PHP versions :

* PHP 5.2.17
* PHP 5.3.29
* PHP 5.4.45
* PHP 5.5.38
* PHP 5.6.40
* PHP 7.0.33
* PHP 7.1.27
* PHP 7.2.16
* PHP 7.3.3

Currently 107 extensions are referenced in the database.

For future versions, see the `CHANGELOG` file.

# Example

See `examples/useExtensionFactory.php` script to learn how to access to informations in database.

# Requirements

* PHP 7.1 or greater
* PHPUnit 7 or greater (if you want to run unit tests)

# Authors

* Laurent Laville
* Remi Collet (contributor on many extensions and unit tests)

# License

This handler is licensed under the BSD-3-clauses License - see the `LICENSE` file for details
