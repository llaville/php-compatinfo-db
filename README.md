<!-- markdownlint-disable MD013 -->
# PHP CompatInfoDB

| Releases        |                   Branch                    | PHP | Packagist | License | Documentation |
|:----------------|:-------------------------------------------:|:---------------:|:---------------:|:---------------:|:---------------:|
| Stable v5.13.x  | [![Branch 5.13][Branch_513x-img]][Branch_513x] | [![Minimum PHP Version)][PHPVersion_513x-img]][PHPVersion_513x] | [![Stable Version 5.13][Packagist_513x-img]][Packagist_513x] | [![License 5.13][License_513x-img]][License_513x] | [![Documentation 5.13][Documentation_513x-img]][Documentation_513x] |
| Unstable v6.0.x | [![Branch 6.0][Branch_60x-img]][Branch_60x] | [![Minimum PHP Version)][PHPVersion_60x-img]][PHPVersion_60x] | [![Stable Version 6.0][Packagist_60x-img]][Packagist_60x] | [![License 6.0][License_60x-img]][License_60x] | [![Documentation 6.0][Documentation_60x-img]][Documentation_60x] |

[Branch_513x-img]: https://img.shields.io/badge/branch-5.13-orange
[Branch_513x]: https://github.com/llaville/php-compatinfo-db/tree/5.13
[PHPVersion_513x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/5.13.0
[PHPVersion_513x]: https://www.php.net/supported-versions.php
[Packagist_513x-img]: https://img.shields.io/badge/packagist-v5.13.0-blue
[Packagist_513x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_513x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_513x]: https://github.com/llaville/php-compatinfo-db/blob/5.13/LICENSE
[Documentation_513x-img]: https://img.shields.io/badge/documentation-v5.13-green
[Documentation_513x]: https://github.com/llaville/php-compatinfo-db/tree/5.13/docs

[Branch_60x-img]: https://img.shields.io/badge/branch-6.0-orange
[Branch_60x]: https://github.com/llaville/php-compatinfo-db/tree/6.0
[PHPVersion_60x-img]: https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db/6.0.0
[PHPVersion_60x]: https://www.php.net/supported-versions.php
[Packagist_60x-img]: https://img.shields.io/badge/packagist-v6.0.0-blue
[Packagist_60x]: https://packagist.org/packages/bartlett/php-compatinfo-db
[License_60x-img]: https://img.shields.io/packagist/l/bartlett/php-compatinfo-db
[License_60x]: https://github.com/llaville/php-compatinfo-db/blob/6.0/LICENSE
[Documentation_60x-img]: https://img.shields.io/badge/documentation-v6.0-green
[Documentation_60x]: https://github.com/llaville/php-compatinfo-db/tree/6.0/docs

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
 | `5.0.x`  to `5.13.x` | `>= 8.0` and `< 8.3` | `7.0`        |
 | `6.0.x`              | `>= 8.1`             | `8.0`        |

## Documentation

All the documentation is available on [website](https://llaville.github.io/php-compatinfo-db/5.x),
generated from the [docs](https://github.com/llaville/php-compatinfo-db/tree/master/docs) folder.

* [Getting Started](docs/getting-started.md).

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)
