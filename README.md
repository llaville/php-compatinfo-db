<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases       |                     Branch                     |                               PHP                               |                          Packagist                           |                      License                      |                            Documentation                            |
|:---------------|:----------------------------------------------:|:---------------------------------------------------------------:|:------------------------------------------------------------:|:-------------------------------------------------:|:-------------------------------------------------------------------:|
| Stable v5.14.x | [![Branch 5.14][Branch_514x-img]][Branch_514x] | [![Minimum PHP Version)][PHPVersion_514x-img]][PHPVersion_514x] | [![Stable Version 5.14][Packagist_514x-img]][Packagist_514x] | [![License 5.14][License_514x-img]][License_514x] | [![Documentation 5.14][Documentation_514x-img]][Documentation_514x] |
| Stable v6.31.x | [![Branch 6.31][Branch_631x-img]][Branch_631x] | [![Minimum PHP Version)][PHPVersion_631x-img]][PHPVersion_631x] | [![Stable Version 6.31][Packagist_631x-img]][Packagist_631x] | [![License 6.31][License_631x-img]][License_631x] | [![Documentation 6.31][Documentation_631x-img]][Documentation_631x] |
| Stable v6.32.x | [![Branch 6.32][Branch_632x-img]][Branch_632x] | [![Minimum PHP Version)][PHPVersion_632x-img]][PHPVersion_632x] | [![Stable Version 6.32][Packagist_632x-img]][Packagist_632x] | [![License 6.32][License_632x-img]][License_632x] | [![Documentation 6.32][Documentation_632x-img]][Documentation_632x] |

[Branch_514x-img]: https://img.shields.io/badge/branch-5.14-orange
[Branch_514x]: https://github.com/llaville/php-compatinfo-db/tree/5.14
[PHPVersion_514x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/5.14.0
[PHPVersion_514x]: https://www.php.net/supported-versions.php
[Packagist_514x-img]: https://img.shields.io/badge/packagist-v5.14.0-blue
[Packagist_514x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_514x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_514x]: https://github.com/llaville/php-compatinfo-db/blob/5.14/LICENSE
[Documentation_514x-img]: https://img.shields.io/badge/documentation-v5.14-green
[Documentation_514x]: https://github.com/llaville/php-compatinfo-db/tree/5.14/docs

[Branch_631x-img]: https://img.shields.io/badge/branch-6.31-orange
[Branch_631x]: https://github.com/llaville/php-compatinfo-db/tree/6.31
[PHPVersion_631x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.31.0
[PHPVersion_631x]: https://www.php.net/supported-versions.php
[Packagist_631x-img]: https://img.shields.io/badge/packagist-v6.31.0-blue
[Packagist_631x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_631x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_631x]: https://github.com/llaville/php-compatinfo-db/blob/6.31/LICENSE
[Documentation_631x-img]: https://img.shields.io/badge/documentation-v6.31-green
[Documentation_631x]: https://github.com/llaville/php-compatinfo-db/tree/6.31/docs

[Branch_632x-img]: https://img.shields.io/badge/branch-6.32-orange
[Branch_632x]: https://github.com/llaville/php-compatinfo-db/tree/6.32
[PHPVersion_632x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.32.0
[PHPVersion_632x]: https://www.php.net/supported-versions.php
[Packagist_632x-img]: https://img.shields.io/badge/packagist-v6.32.1-blue
[Packagist_632x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_632x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_632x]: https://github.com/llaville/php-compatinfo-db/blob/6.32/LICENSE
[Documentation_632x-img]: https://img.shields.io/badge/documentation-v6.32-green
[Documentation_632x]: https://github.com/llaville/php-compatinfo-db/tree/6.32/docs

Main goal of this project is to provide a standalone database that references
all functions, constants, classes, interfaces on PHP standard distribution and about 110 extensions.

This database is currently only support by its initial project [php-compatinfo](https://github.com/llaville/php-compatinfo)

## Version Compatibility

 | CompatInfoDB         | PHP                  | CompatInfo          |
 |----------------------|----------------------|---------------------|
 | `3.6.x`  to `3.16.x` | `>= 7.2`             | `5.5`               |
 | `3.6.x`  to `3.16.x` | `>= 7.4`             | `6.0`               |
 | `3.17.x` to `3.18.x` | `>= 7.4`             | `6.1`               |
 | `4.0.x`  to `4.1.x`  | `>= 7.4`             | `6.2`, `6.3`        |
 | `4.2.x`  to `4.5.x`  | `>= 7.4`             | `6.4`               |
 | `4.6.x`  to `4.11.x` | `>= 7.4`             | `6.5`               |
 | `5.0.x`  to `5.14.x` | `>= 8.0` and `< 8.3` | `7.0`               |
 | `6.0.x`  to `6.31.x` | `>= 8.1` and `< 8.6` | `7.0`, `7.1`, `7.2` |
 | `6.32.x`             | `>= 8.2` and `< 8.6` | `7.3`               |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/6.32),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/6.32/docs) folder.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
