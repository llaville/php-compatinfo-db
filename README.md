[![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/php-compatinfo-db.svg?style=flat-square)](https://packagist.org/packages/bartlett/php-compatinfo-db)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg?style=flat-square)](https://php.net/)

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

Version 1.38.1 support informations to latest PHP versions :

* PHP 5.2.17
* PHP 5.3.29
* PHP 5.4.45
* PHP 5.5.38
* PHP 5.6.38
* PHP 7.0.32
* PHP 7.1.24
* PHP 7.2.12

Currently 107 extensions are referenced in the database.

For future versions, see the `CHANGELOG` file.

# Example

See `examples/useExtensionFactory.php` script to learn how to access to informations in database.

# Requirements

PHP 5.5 or greater
PHPUnit 5 or 6 (if you want to run unit tests)

# PHP5 users only

Remove `composer.lock` to be able to install correct dependencies.

Content of this file in repository if for PHP7 users.

# Authors

* Laurent Laville
* Remi Collet (contributor on many extensions and unit tests)

# License

This handler is licensed under the BSD-3-clauses License - see the `LICENSE` file for details
