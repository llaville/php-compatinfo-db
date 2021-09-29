<!-- markdownlint-disable MD013 MD024 -->
# Changes in 3.x

All notable changes of the CompatInfoDB 2 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Changed

- Uploadprogress updated to version 2.0.0 (stable)
- Zip reference updated to version 1.19.5 (stable)

### Fixed

- [#88](https://github.com/llaville/php-compatinfo-db/issues/88) : OPENSSL_SSLV23_PADDING is optional

## [3.11.0] - 2021-09-25

### Added

- PHP 8.0.11 support
- PHP 7.4.24 support
- PHP 7.3.31 support

### Changed

- http reference updated to version 4.2.1 (stable)
- mailparse reference updated to version 3.1.2 (stable)
- Xhprof reference updated to version 2.3.5 (stable)
- Zip reference updated to version 1.19.4 (stable)

## [3.10.0] - 2021-08-28

### Added

- [Mega-Linter](https://github.com/nvuillam/mega-linter) support as QA tool to avoid technical debt
- PHP 8.0.10 support
- PHP 7.4.23 support
- PHP 7.3.30 support

### Changed

- Igbinary reference updated to version 3.2.6 (stable)
- Xhprof reference updated to version 2.3.4 (stable)

## [3.9.0] - 2021-07-31

### Added

- `uuid` extension support
- `xlswriter` extension support
- EXPERIMENTAL support to PHP 8.1.x-dev
- PHP 8.0.9 support
- PHP 7.4.22 support

### Changed

- `db:list` command show unsupported extensions
- `db:list` command result can be filtered on extension type (bundle, pecl)
- `db:list` command result can be filtered on extension name
- [Disable symfony deprecation warnings in PHPUnit tests](https://nerdpress.org/2019/08/29/disable-symfony-deprecation-warnings-in-phpunit-tests/)
- Ast reference updated to version 1.0.14 (stable)
- Igbinary reference updated to version 3.2.4 (stable)
- Imagick reference updated to version 3.5.1 (stable)

### Removed

- drop support of [haru](https://pecl.php.net/package/haru) extension not maintained since 2012 (only PHP 5 compatible)
- drop support of [htscanner](https://pecl.php.net/package/htscanner) extension not maintained since 2012 (only PHP 5 compatible)
- drop support of [inclued](https://pecl.php.net/package/inclued) extension not maintained since 2012 (only PHP 5 compatible)
- drop support of [libevent](https://pecl.php.net/package/libevent) extension not maintained since 2013 (only PHP 5 compatible)
- drop support of [pdflib](https://pecl.php.net/package/pdflib) extension not maintained since 2019 (only PHP 5 compatible)
- drop support of [pthreads](https://pecl.php.net/package/pthreads) extension not maintained since 2016
- drop support of [riak](https://pecl.php.net/package/riak) extension not maintained since 2014 (only PHP 5 compatible)
- drop support of [sphinx](https://pecl.php.net/package/sphinx) extension not maintained since 2015 (only PHP 5 compatible)

## [3.8.0] - 2021-07-06

### Added

- PHP 8.0.8 support
- PHP 7.4.21 support
- PHP 7.3.29 support

### Changed

- Xhprof reference updated to version 2.3.3 (stable)

## [3.7.1] - 2021-06-28

### Added

- `doctor` command is now able to display library versions of the current platform in the PHP dependencies summary section

### Fixed

- [#78](https://github.com/llaville/php-compatinfo-db/issues/78) Zip reference (thanks to @remicollet for reporting)
- [#79](https://github.com/llaville/php-compatinfo-db/issues/79) Imagick reference (thanks to @remicollet for reporting)

## [3.7.0] - 2021-06-24

### Added

- PHP 8.0.7 support
- PHP 7.4.20 support
- ability to display dependency constraints on each extension with the `db:show` command
- display PHP version of each function parameters with the `db:show <extenion> --functions` command
- summary on `doctor` command (with status code 0: OK, 1:dependency constraint failures, 2:test failures, 3:dependency and test failures)

### Changed

- Ast reference updated to version 1.0.12 (stable)
- Igbinary reference updated to version 3.2.3 (stable)
- Imagick reference updated to version 3.5.0 (stable)
- Mcrypt reference bundled with PHP before 7.2.0 is now compatible with PECL version 1.0.0 or greater
- Zip reference updated to version 1.19.3 (stable)
- Use `symfony/cache` implementation rather than `doctrine/cache` where driver implementations were removed in 2.0x (see <https://github.com/doctrine/cache/blob/2.0.x/UPGRADE-1.11.md>)

## [3.6.0] - 2021-05-13

### Added

- PHP 8.0.6 support
- PHP 7.4.19 support
- new `Dependencies` column in `db:show` command output that will display librairies dependency constraints
- new `doctor` command to help to debug issues by checking current installation

### Changed

- Xhprof reference updated to version 2.3.2 (stable)

### Fixed

- ClassHydrator to handle dependencies
- [#75](https://github.com/llaville/php-compatinfo-db/issues/75) tests failure (thanks to @remicollet for reporting)

## [3.5.0] - 2021-05-03

### Added

- PHP 8.0.5 support
- PHP 7.4.18 support
- PHP 7.3.28 support
- Support to [rdkafka](https://pecl.php.net/package/rdkafka) extension (Kafka client based on librdkafka)
- new `db:build` command for developers only (using `APP_ENV=dev`) to generate JSON files to add a new extension
- add shortcut to option `all` of command `db:list`
- `flags` property in Class/Function entities to identify (public/protected/private methods, abstract, final, static classes/methods)
- With new `flags` column, `db:show <extension> --methods` command is now able to display when method is abstract (A), final (F) or static (S)

**Caution** DB structure changed

### Changed

- Ast reference updated to version 1.0.11 (stable)
- Http reference updated to version 4.1.0 (stable)
- Igbinary reference updated to version 3.2.2 (stable)
- Mcrypt reference updated to version 1.0.4 (stable) for PHP 7.2.0 or greater
- Redis reference updated to version 5.3.4 (stable)
- Sync reference updated to version 1.1.2 (stable)
- Wddx was unbundled from PHP since version 7.4.0
- Xdebug reference updated to version 3.0.4 (stable)
- Xhprof reference updated to version 2.3.1 (beta)
- Clean-up JSON files by removing static (false) definition that is the default

As support to PHP 5 was dropped in release 2.0.0, we removed following extensions :

- apc
- ereg
- mhash
- mongo
- mysql
- sqlite

Some other extensions have support limited :

- wddx (PHP 5.2 to PHP 7.3), see <https://wiki.php.net/rfc/deprecate-and-remove-ext-wddx>

### Fixed

- [#70](https://github.com/llaville/php-compatinfo-db/issues/70) test failure for pcre extension (thanks to @remicollet for reporting)
- [#72](https://github.com/llaville/php-compatinfo-db/issues/72) Checks that elements available in extension are define in Reference
- [#73](https://github.com/llaville/php-compatinfo-db/issues/73) Wrong static property in JSON files did not fail unit tests
- `Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ConstantRepository::getConstantByName` when name is lowercase
- Wrong assertion in `Bartlett\CompatInfoDb\Tests\Reference\GenericTest::provideReferenceValues` about `ext.min` property
- Json reference version related to specific rules (see ExtensionVersionProviderTrait)

- Warning :
  - `enchant_dict_add_to_personal`: Alias of enchant_dict_add is DEPRECATED as of PHP 8.0.0
  - `enchant_dict_is_in_session` : Alias of enchant_dict_is_added is DEPRECATED as of PHP 8.0.0

## [3.4.2] - 2021-03-13

### Fixed

- Autoloader regression when using this dependency in another project like php-compatinfo

## [3.4.1] - 2021-03-13

**CAUTION:** uses `config/bootstrap.php` to apply autoloader and initialize environment variables (`APP_ENV` and `APP_PROXY_DIR`)

### Fixed

- [#69](https://github.com/llaville/php-compatinfo-db/issues/69) Proxy files management is broken (thanks to @remicollet for reporting)

## [3.4.0] - 2021-03-12

### Added

- Autogenerate Doctrine proxy files.
  - Sets environment variable `APP_PROXY_DIR` to defines the directory where Doctrine generates any proxy classes.
  - Default is `/tmp/bartlett/php-compatinfo-db/<VERSION>/proxies` (with `VERSION` current application version)

## [3.3.0] - 2021-03-09

### Added

- PHP 7.4.16 support
- PHP 8.0.3 support

### Changed

- Add progress flag on `db:init` command to display a progress bar only on demand (for slow system)
- Use [PHPStan](https://github.com/phpstan/phpstan/) for static analysis in Github Actions Workflows
- Use the `ramsey/composer-install` action to install dependencies
- APCu reference updated to version 5.1.20 (stable)
- Ssh2 reference updated to version 1.3.1 (beta)
- Xdebug reference updated to version 3.0.3 (stable)

### Fixed

- [#66](https://github.com/llaville/php-compatinfo-db/issues/66) oci8 test failure
- The flags parameter of `preg_replace_callback` was added in PHP 7.4.0

## [3.2.0] - 2021-02-09

### Added

- new repositories for easy access of single elements (class, interface, method, function, constant)
- PHP 7.3.27 support
- PHP 7.4.15 support
- PHP 8.0.2 support

### Changed

- DB optimization: removed all empty (blank) properties by NULL value
- Redis reference updated to version 5.3.3 (stable)

### Fixed

- [#65](https://github.com/llaville/php-compatinfo-db/issues/65) Make the database compatible with all PHP versions

Because Doctrine ORM v2 has following issue

- [7598](https://github.com/doctrine/orm/issues/7598) Unable to create a proxy for a final class
we removed `final` keyword from Persistence Entity Objects

## [3.1.1] - 2021-01-20

### Changed

- Xmldiff reference updated to version 1.1.3 (stable)

### Fixed

Thanks to @remicollet to reported all this four issues:

- [#61](https://github.com/llaville/php-compatinfo-db/issues/61) Keep Symfony 4.4 Backward Compatibility
- [#62](https://github.com/llaville/php-compatinfo-db/issues/62) OCI8 reference issue
- [#63](https://github.com/llaville/php-compatinfo-db/issues/63) http reference issue
- [#64](https://github.com/llaville/php-compatinfo-db/issues/64) xmlrpc reference issue

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

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/3.11.0...HEAD
[3.11.0]: https://github.com/llaville/php-compatinfo-db/compare/3.10.0...3.11.0
[3.10.0]: https://github.com/llaville/php-compatinfo-db/compare/3.9.0...3.10.0
[3.9.0]: https://github.com/llaville/php-compatinfo-db/compare/3.8.0...3.9.0
[3.8.0]: https://github.com/llaville/php-compatinfo-db/compare/3.7.1...3.8.0
[3.7.1]: https://github.com/llaville/php-compatinfo-db/compare/3.7.0...3.7.1
[3.7.0]: https://github.com/llaville/php-compatinfo-db/compare/3.6.0...3.7.0
[3.6.0]: https://github.com/llaville/php-compatinfo-db/compare/3.5.0...3.6.0
[3.5.0]: https://github.com/llaville/php-compatinfo-db/compare/3.4.2...3.5.0
[3.4.2]: https://github.com/llaville/php-compatinfo-db/compare/3.4.1...3.4.2
[3.4.1]: https://github.com/llaville/php-compatinfo-db/compare/3.4.0...3.4.1
[3.4.0]: https://github.com/llaville/php-compatinfo-db/compare/3.3.0...3.4.0
[3.3.0]: https://github.com/llaville/php-compatinfo-db/compare/3.2.0...3.3.0
[3.2.0]: https://github.com/llaville/php-compatinfo-db/compare/3.1.1...3.2.0
[3.1.1]: https://github.com/llaville/php-compatinfo-db/compare/3.1.0...3.1.1
[3.1.0]: https://github.com/llaville/php-compatinfo-db/compare/3.0.2...3.1.0
[3.0.2]: https://github.com/llaville/php-compatinfo-db/compare/3.0.1...3.0.2
[3.0.1]: https://github.com/llaville/php-compatinfo-db/compare/3.0.0...3.0.1
[3.0.0]: https://github.com/llaville/php-compatinfo-db/compare/2.19.0...3.0.0
