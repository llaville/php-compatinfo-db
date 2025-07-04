<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases       |                     Branch                     |                               PHP                               |                          Packagist                           |                      License                      |                            Documentation                            |
|:---------------|:----------------------------------------------:|:---------------------------------------------------------------:|:------------------------------------------------------------:|:-------------------------------------------------:|:-------------------------------------------------------------------:|
| Stable v5.14.x | [![Branch 5.14][Branch_514x-img]][Branch_514x] | [![Minimum PHP Version)][PHPVersion_514x-img]][PHPVersion_514x] | [![Stable Version 5.14][Packagist_514x-img]][Packagist_514x] | [![License 5.14][License_514x-img]][License_514x] | [![Documentation 5.14][Documentation_514x-img]][Documentation_514x] |
| Stable v6.19.x | [![Branch 6.19][Branch_619x-img]][Branch_619x] | [![Minimum PHP Version)][PHPVersion_619x-img]][PHPVersion_619x] | [![Stable Version 6.19][Packagist_619x-img]][Packagist_619x] | [![License 6.19][License_619x-img]][License_619x] | [![Documentation 6.19][Documentation_619x-img]][Documentation_619x] |
| Stable v6.20.x | [![Branch 6.20][Branch_620x-img]][Branch_620x] | [![Minimum PHP Version)][PHPVersion_620x-img]][PHPVersion_620x] | [![Stable Version 6.20][Packagist_620x-img]][Packagist_620x] | [![License 6.20][License_620x-img]][License_620x] | [![Documentation 6.20][Documentation_620x-img]][Documentation_620x] |

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

[Branch_619x-img]: https://img.shields.io/badge/branch-6.19-orange
[Branch_619x]: https://github.com/llaville/php-compatinfo-db/tree/6.19
[PHPVersion_619x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.19.0
[PHPVersion_619x]: https://www.php.net/supported-versions.php
[Packagist_619x-img]: https://img.shields.io/badge/packagist-v6.19.0-blue
[Packagist_619x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_619x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_619x]: https://github.com/llaville/php-compatinfo-db/blob/6.19/LICENSE
[Documentation_619x-img]: https://img.shields.io/badge/documentation-v6.19-green
[Documentation_619x]: https://github.com/llaville/php-compatinfo-db/tree/6.19/docs

[Branch_620x-img]: https://img.shields.io/badge/branch-6.20-orange
[Branch_620x]: https://github.com/llaville/php-compatinfo-db/tree/6.20
[PHPVersion_620x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.20.0
[PHPVersion_620x]: https://www.php.net/supported-versions.php
[Packagist_620x-img]: https://img.shields.io/badge/packagist-v6.20.0-blue
[Packagist_620x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_620x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_620x]: https://github.com/llaville/php-compatinfo-db/blob/6.20/LICENSE
[Documentation_620x-img]: https://img.shields.io/badge/documentation-v6.20-green
[Documentation_620x]: https://github.com/llaville/php-compatinfo-db/tree/6.20/docs

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
 | `6.0.x`  to `6.20.x` | `>= 8.1`             | `7.0`, `7.1`, `7.2` |
 | `7.0.x`              | `>= 8.2`             | `8.0`               |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/6.20),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/6.20/docs) folder.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
