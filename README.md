<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases       |                     Branch                     |                               PHP                               |                          Packagist                           |                      License                      |                            Documentation                            |
|:---------------|:----------------------------------------------:|:---------------------------------------------------------------:|:------------------------------------------------------------:|:-------------------------------------------------:|:-------------------------------------------------------------------:|
| Stable v5.14.x | [![Branch 5.14][Branch_514x-img]][Branch_514x] | [![Minimum PHP Version)][PHPVersion_514x-img]][PHPVersion_514x] | [![Stable Version 5.14][Packagist_514x-img]][Packagist_514x] | [![License 5.14][License_514x-img]][License_514x] | [![Documentation 5.14][Documentation_514x-img]][Documentation_514x] |
| Stable v6.18.x | [![Branch 6.18][Branch_618x-img]][Branch_618x] | [![Minimum PHP Version)][PHPVersion_618x-img]][PHPVersion_618x] | [![Stable Version 6.18][Packagist_618x-img]][Packagist_618x] | [![License 6.18][License_618x-img]][License_618x] | [![Documentation 6.18][Documentation_618x-img]][Documentation_618x] |
| Stable v6.19.x | [![Branch 6.19][Branch_619x-img]][Branch_619x] | [![Minimum PHP Version)][PHPVersion_619x-img]][PHPVersion_619x] | [![Stable Version 6.19][Packagist_619x-img]][Packagist_619x] | [![License 6.19][License_619x-img]][License_619x] | [![Documentation 6.19][Documentation_619x-img]][Documentation_619x] |

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

[Branch_618x-img]: https://img.shields.io/badge/branch-6.18-orange
[Branch_618x]: https://github.com/llaville/php-compatinfo-db/tree/6.18
[PHPVersion_618x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.18.0
[PHPVersion_618x]: https://www.php.net/supported-versions.php
[Packagist_618x-img]: https://img.shields.io/badge/packagist-v6.18.0-blue
[Packagist_618x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_618x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_618x]: https://github.com/llaville/php-compatinfo-db/blob/6.18/LICENSE
[Documentation_618x-img]: https://img.shields.io/badge/documentation-v6.18-green
[Documentation_618x]: https://github.com/llaville/php-compatinfo-db/tree/6.18/docs

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
 | `6.0.x`  to `6.19.x` | `>= 8.1`             | `7.0`, `7.1`, `7.2` |
 | `7.0.x`              | `>= 8.2`             | `8.0`               |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/6.19),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/6.19/docs) folder.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
