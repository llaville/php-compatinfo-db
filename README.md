# PHP CompatInfoDB

| Stable |
|:------:|
| [![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/php-compatinfo-db)](https://packagist.org/packages/bartlett/php-compatinfo-db) |
| [![Minimum PHP Version)](https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db)](https://php.net/) |
| [![Branch Master](https://img.shields.io/badge/branch-master-blue)](https://github.com/llaville/php-compatinfo-db) |
| [![Tests](https://github.com/llaville/php-compatinfo-db/workflows/Tests/badge.svg)](https://github.com/llaville/php-compatinfo-db/actions) |

Main goal of this project is to provide a standalone database that references
all functions, constants, classes, interfaces on PHP standard distribution and about 100 extensions.

This database is currently only support by its initial project [php-compatinfo](https://github.com/llaville/php-compat-info)

## Features

* a Symfony console application to handle data (json files) of the SQL database is provided on CLI API

More than 100 extensions (PHP standard distribution, but also PECL) are currently support :

* reference all functions
* reference all constants
* reference all classes
* reference all classes constants
* reference all interfaces
* reference all methods
* reference all ini entries
* reference all releases

## [Supported Versions](./SUPPORTED-VERSIONS.md)

| Major Version | Release    | PHP compatibility    |
|---------------|------------|----------------------|
| 3.0.0         | 2020-12-29 | PHP >= 7.2.0         |
| 2.0.0         | 2019-01-19 | 7.1.0 <= PHP < 8.0.0 |
| 1.0.0         | 2015-11-24 | PHP >= 5.4.0         |

Currently, 109 extensions referenced in the database.

For future versions, see the `CHANGELOG-3.x.md` file.

## Examples

See `examples/useExtensionFactory.php` script to learn how to access to information in database.

## Requirements

* PHP 7.2 or greater
* PHPUnit 8 or greater (if you want to run unit tests)

## Installation

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/php-compatinfo-db
```

## Configuring the Database

The database connection information is stored as an environment variable called `DATABASE_URL`.

```
# to use mysql:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

# to use mariadb:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"

# to use sqlite:
# DATABASE_URL="sqlite:///${HOME}/.cache/bartlett/compatinfo-db.sqlite"

# to use postgresql:
# DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8"
```

If you change database connection, you have to run following commands:
- `vendor/bin/doctrine orm:schema-tool:create`
- `bin/compatinfo-db db:init`

At dependencies installation, Composer use the sqlite back-end. You need to set up in your environment the `DATABASE_URL` variable.

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

This project is license under the BSD-3-Clause License - see the [LICENSE](https://github.com/llaville/php-compatinfo-db/blob/master/LICENSE) file for details
