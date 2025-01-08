<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases       |                     Branch                     |                               PHP                               |                          Packagist                           |                      License                      |                            Documentation                            |
|:---------------|:----------------------------------------------:|:---------------------------------------------------------------:|:------------------------------------------------------------:|:-------------------------------------------------:|:-------------------------------------------------------------------:|
| Stable v5.14.x | [![Branch 5.14][Branch_514x-img]][Branch_514x] | [![Minimum PHP Version)][PHPVersion_514x-img]][PHPVersion_514x] | [![Stable Version 5.14][Packagist_514x-img]][Packagist_514x] | [![License 5.14][License_514x-img]][License_514x] | [![Documentation 5.14][Documentation_514x-img]][Documentation_514x] |
| Stable v6.13.x | [![Branch 6.13][Branch_613x-img]][Branch_613x] | [![Minimum PHP Version)][PHPVersion_613x-img]][PHPVersion_613x] | [![Stable Version 6.13][Packagist_613x-img]][Packagist_613x] | [![License 6.13][License_613x-img]][License_613x] | [![Documentation 6.13][Documentation_613x-img]][Documentation_613x] |
| Stable v6.14.x | [![Branch 6.14][Branch_614x-img]][Branch_614x] | [![Minimum PHP Version)][PHPVersion_614x-img]][PHPVersion_614x] | [![Stable Version 6.14][Packagist_614x-img]][Packagist_614x] | [![License 6.14][License_614x-img]][License_614x] | [![Documentation 6.14][Documentation_614x-img]][Documentation_614x] |

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

[Branch_613x-img]: https://img.shields.io/badge/branch-6.13-orange
[Branch_613x]: https://github.com/llaville/php-compatinfo-db/tree/6.13
[PHPVersion_613x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.13.0
[PHPVersion_613x]: https://www.php.net/supported-versions.php
[Packagist_613x-img]: https://img.shields.io/badge/packagist-v6.13.2-blue
[Packagist_613x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_613x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_613x]: https://github.com/llaville/php-compatinfo-db/blob/6.13/LICENSE
[Documentation_613x-img]: https://img.shields.io/badge/documentation-v6.13-green
[Documentation_613x]: https://github.com/llaville/php-compatinfo-db/tree/6.13/docs

[Branch_614x-img]: https://img.shields.io/badge/branch-6.14-orange
[Branch_614x]: https://github.com/llaville/php-compatinfo-db/tree/6.14
[PHPVersion_614x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.14.0
[PHPVersion_614x]: https://www.php.net/supported-versions.php
[Packagist_614x-img]: https://img.shields.io/badge/packagist-v6.14.0-blue
[Packagist_614x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_614x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_614x]: https://github.com/llaville/php-compatinfo-db/blob/6.14/LICENSE
[Documentation_614x-img]: https://img.shields.io/badge/documentation-v6.14-green
[Documentation_614x]: https://github.com/llaville/php-compatinfo-db/tree/6.14/docs

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
 | `6.0.x`  to `6.14.x` | `>= 8.1`             | `7.0`, `7.1` |
 | `6.14.x`             | `>= 8.1`             | `7.2`        |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/6.14),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/6.14/docs) folder.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
