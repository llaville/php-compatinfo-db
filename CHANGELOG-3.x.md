# Changes in 3.x

All notable changes of the CompatInfoDB 2 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Fixed

- [#61](https://github.com/llaville/php-compatinfo-db/issues/61) Keep Symfony 4.4 Backward Compatibility
- [#62](https://github.com/llaville/php-compatinfo-db/issues/62) OCI8 reference issue

## [3.1.0] - 2021-01-09

### Added

- PHP 7.3.26 support
- PHP 7.4.14 support
- PHP 8.0.1 support

### Changed

- replaced `return 0` by `return Command::SUCCESS` and `return 1` by `return Command::FAILURE` in Console commands

### Fixed

- assure application version used when initializing platforms and displaying them

## [3.0.2] - 2021-01-06

### Fixed

- bump new Application version

### Removed

- clean-up old component from v2.x

## [3.0.1] - 2021-01-06

### Changed

- clean-up duplicated Odbc unit tests available both in PhpBundle and PhpPecl (keep PhpBundle)
- reorganizes test suites main list to allow suite filter feature of PHPUnit
- bump new year in LICENSE file
- removes `humbug/box` dependency
- initializes SQLite database when invoking `composer install` or `composer update` commands
- Xdebug reference updated to version 3.0.2 (stable)
- Yac reference updated to version 2.3.0 (stable)

## [3.0.0] - 2020-12-29

### Added

- PHP 7.3.24 and 7.3.25 support
- PHP 7.4.12 and 7.4.13 support
- Doctrine ORM to support more Back-End than just native SQLite3, and handle domain of DDD architecture
- [#49](https://github.com/llaville/php-compatinfo-db/issues/49) PHP 8.0 support
- [#52](https://github.com/llaville/php-compatinfo-db/issues/52) Configuration - read it from a compatible PSR11 container
- [#59](https://github.com/llaville/php-compatinfo-db/issues/59) Phar distribution automated create/update with composer

### Changed

- removed `bartlett:` namespace prefix and all commands
- new `bartlett:db:release` command combines old `bartlett:db:release:php` and `bartlett:db:publish:php` commands that were removed
- [#50](https://github.com/llaville/php-compatinfo-db/issues/50) Dependency-Injection with Symfony component
replace old `ContainerService` that was introduced in version 2.13
- [#51](https://github.com/llaville/php-compatinfo-db/issues/51) CommandBus with Symfony Messenger component
- [#54](https://github.com/llaville/php-compatinfo-db/issues/54) update Sqlite3 reference to support PHP 8.0
- [#56](https://github.com/llaville/php-compatinfo-db/issues/56) Lite alternative to `laminas-diagnostics` solution
- APCu reference updated to version 5.1.19 (stable)
- Igbinary reference updated to version 3.2.1 (stable)
- Jsmin reference updated to version 3.0.0 (stable)
- Memcache reference updated to version 8.0 (stable)
- Msgpack reference updated to version 2.1.2 (stable)
- OCI8 reference updated to version 3.0.1 (stable)
- Pdflib reference updated to version 4.1.4 (stable)
- Raphf reference updated to version 2.0.1 (stable)
- Rar reference updated to version 4.2.0 (stable)
- Redis reference updated to version 5.3.2 (stable)
- Uopz reference updated to version 6.1.2 (stable)
- Xdebug reference updated to version 3.0.1 (stable)
- Xhprof reference updated to version 2.2.3 (stable)
- Yac reference updated to version 2.2.1 (stable)
- Yaml reference updated to version 2.2.1 (stable)
- Zip reference updated to version 1.19.2 (stable)

### Removed

- `bartlett:db:build:ext` command

### Fixed

- [#13](https://github.com/llaville/php-compatinfo-db/issues/13) Missing Reference entries not detected by standard suite tests
- [#48](https://github.com/llaville/php-compatinfo-db/issues/48) GenericTest - checkValuesFromReference failed to proceed good assertions
- [#55](https://github.com/llaville/php-compatinfo-db/issues/55) Wrong assertion results when method checks
- [#57](https://github.com/llaville/php-compatinfo-db/issues/57) GenericTest - function_exists failed to proceed expected assertion with Polyfills

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/3.0.2...HEAD
[3.0.2]: https://github.com/llaville/php-compatinfo-db/compare/3.0.1...3.0.2
[3.0.1]: https://github.com/llaville/php-compatinfo-db/compare/3.0.0...3.0.1
[3.0.0]: https://github.com/llaville/php-compatinfo-db/compare/2.19.0...3.0.0
