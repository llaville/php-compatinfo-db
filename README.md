<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases        |                     Branch                     |                               PHP                               |                          Packagist                           |                      License                      |                            Documentation                            |
|:----------------|:----------------------------------------------:|:---------------------------------------------------------------:|:------------------------------------------------------------:|:-------------------------------------------------:|:-------------------------------------------------------------------:|
| Stable v5.14.x  | [![Branch 5.14][Branch_514x-img]][Branch_514x] | [![Minimum PHP Version)][PHPVersion_514x-img]][PHPVersion_514x] | [![Stable Version 5.14][Packagist_514x-img]][Packagist_514x] | [![License 5.14][License_514x-img]][License_514x] | [![Documentation 5.14][Documentation_514x-img]][Documentation_514x] |
| Stable v6.0.x   |  [![Branch 6.0][Branch_60x-img]][Branch_60x]   |  [![Minimum PHP Version)][PHPVersion_60x-img]][PHPVersion_60x]  |  [![Stable Version 6.0][Packagist_60x-img]][Packagist_60x]   |  [![License 6.0][License_60x-img]][License_60x]   |  [![Documentation 6.0][Documentation_60x-img]][Documentation_60x]   |
| Upcoming v6.1.x |  [![Branch 6.1][Branch_61x-img]][Branch_61x]   |  [![Minimum PHP Version)][PHPVersion_61x-img]][PHPVersion_61x]  |  [![Stable Version 6.1][Packagist_61x-img]][Packagist_61x]   |  [![License 6.1][License_61x-img]][License_61x]   |  [![Documentation 6.1][Documentation_61x-img]][Documentation_61x]   |

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

[Branch_60x-img]: https://img.shields.io/badge/branch-6.0-orange
[Branch_60x]: https://github.com/llaville/php-compatinfo-db/tree/6.0
[PHPVersion_60x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.0.0
[PHPVersion_60x]: https://www.php.net/supported-versions.php
[Packagist_60x-img]: https://img.shields.io/badge/packagist-v6.0.2-blue
[Packagist_60x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_60x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_60x]: https://github.com/llaville/php-compatinfo-db/blob/6.0/LICENSE
[Documentation_60x-img]: https://img.shields.io/badge/documentation-v6.0-green
[Documentation_60x]: https://github.com/llaville/php-compatinfo-db/tree/6.0/docs

[Branch_61x-img]: https://img.shields.io/badge/branch-6.1-orange
[Branch_61x]: https://github.com/llaville/php-compatinfo-db/tree/6.1
[PHPVersion_61x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.1.0
[PHPVersion_61x]: https://www.php.net/supported-versions.php
[Packagist_61x-img]: https://img.shields.io/badge/packagist-v6.1.0-blue
[Packagist_61x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_61x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_61x]: https://github.com/llaville/php-compatinfo-db/blob/6.1/LICENSE
[Documentation_61x-img]: https://img.shields.io/badge/documentation-v6.1-green
[Documentation_61x]: https://github.com/llaville/php-compatinfo-db/tree/6.1/docs

Main goal of this project is to provide a standalone database that references
all functions, constants, classes, interfaces on PHP standard distribution and about 110 extensions.

This database is currently only support by its initial project [php-compatinfo](https://github.com/llaville/php-compatinfo)

## Version Compatibility

 | CompatInfoDB         | PHP                  | CompatInfo   |
 |----------------------|----------------------|--------------|
 | `3.6.x`  to `3.16.x` | `>= 7.2`             | `5.5`        |
 | `3.6.x`  to `3.16.x` | `>= 7.4`             | `6.0`        |
 | `3.17.x` to `3.18.x` | `>= 7.4`             | `6.1`        |
 | `4.0.x`  to `4.1.x`  | `>= 7.4`             | `6.2`, `6.3` |
 | `4.2.x`  to `4.5.x`  | `>= 7.4`             | `6.4`        |
 | `4.6.x`  to `4.11.x` | `>= 7.4`             | `6.5`        |
 | `5.0.x`  to `5.14.x` | `>= 8.0` and `< 8.3` | `7.0`        |
 | `6.0.x`  to `6.1.x`  | `>= 8.1`             | `7.0`, `8.0` |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/6.1),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/6.1/docs) folder.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
