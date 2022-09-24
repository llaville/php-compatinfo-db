<!-- markdownlint-disable MD013 MD024 -->
# Changes in 4.x

All notable changes of the CompatInfoDB 4 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

<!-- MARKDOWN-RELEASE:START -->
### Added

- experimental support to PHP 8.2 (under development)
<!-- MARKDOWN-RELEASE:END -->

## [4.4.0] - 2022-09-09

### Added

- PHP 8.1.6, 8.1.7, 8.1.8, 8.1.9 and 8.1.10 support
- PHP 8.0.19, 8.0.20, 8.0.21, 8.0.22 and 8.0.23 support
- PHP 7.4.30 support
- [#123](https://github.com/llaville/php-compatinfo-db/issues/123) : Configuration improvement and add a light Kernel

### Changed

- Ast reference updated to version 1.1.0 (stable)
- Http reference updated to version 4.2.3 (stable)
- Imagick reference updated to version 3.7.0 (stable)
- Mcrypt reference updated to version 1.0.5 (stable)
- Rdkafka reference updated to version 6.0.3 (stable)
- Stomp reference updated to version 2.0.3 (stable)
- XlsWriter reference updated to version 1.5.2 (stable)
- Xdebug reference updated to version 3.1.5 (stable)
- Xhprof reference updated to version 2.3.7 (stable)
- Zip reference updated to version 1.20.1 (stable)

## [4.3.0] - 2022-04-20

### Added

- PHP 8.1.5 support
- PHP 8.0.18 support
- PHP 7.4.29 support

### Changed

- Memcached reference updated to version 3.2.0 (stable)
- Xdebug reference updated to version 3.1.4 (stable)

## [4.2.0] - 2022-03-21

### Added

- [#118](https://github.com/llaville/php-compatinfo-db/issues/118) : Automate creation of new GitHub Release with PHAR version as asset
- [#120](https://github.com/llaville/php-compatinfo-db/issues/120) : New `db:polyfill` command to add polyfill package elements into JSON files. See feature request <https://github.com/llaville/php-compatinfo/issues/237>
  - add [symfony/polyfill-php81](https://github.com/symfony/polyfill/tree/main/src/Php81) polyfill support
  - add [symfony/polyfill-php80](https://github.com/symfony/polyfill/tree/main/src/Php80) polyfill support
  - add [symfony/polyfill-php74](https://github.com/symfony/polyfill/tree/main/src/Php74) polyfill support
  - add [symfony/polyfill-php73](https://github.com/symfony/polyfill/tree/main/src/Php73) polyfill support
  - add [symfony/polyfill-php72](https://github.com/symfony/polyfill/tree/main/src/Php72) polyfill support
  - add [symfony/polyfill-iconv](https://github.com/symfony/polyfill/tree/main/src/Iconv) polyfill support
  - add [symfony/polyfill-mbstring](https://github.com/symfony/polyfill/tree/main/src/Mbstring) polyfill support
  - add [symfony/polyfill-ctype](https://github.com/symfony/polyfill/tree/main/src/Ctype) polyfill support
- PHP 8.0.17 support
- PHP 8.1.4 support

### Changed

- Http reference updated to version 4.2.2 (stable) for PHP 8.x
- Http reference updated to version 3.2.5 (stable) for PHP 7.x
- Mailparse reference updated to version 3.1.3 (stable)
- Memcached reference updated to version 3.2.0RC2 (beta)

### Fixed

- [#119](https://github.com/llaville/php-compatinfo-db/issues/119) : Auto diagnose print its results even if all works fine
- add float limit constants missing (see <https://github.com/llaville/php-compatinfo-db/issues/120#issuecomment-1067757748>)
- add os family constant missing (see <https://github.com/llaville/php-compatinfo-db/issues/120#issuecomment-1067757748>)
- add `sapi_windows_vt100_support` missing function from standard extension

## [4.1.0] - 2022-02-20

### Added

- PHP 8.1.3 support
- PHP 8.0.16 support
- PHP 7.4.28 support

### Changed

- Rdkafka reference updated to version 6.0.1 (stable)
- Redis reference updated to version 5.3.7 (stable)

### Fixed

- [#111](https://github.com/llaville/php-compatinfo-db/issues/111) Cannot build the PHAR version of CompatInfoDB with metadata

## [4.0.0] - 2022-02-04

### Added

- `about` command to display current long version and more information about this package.
- `APP_DATABASE_URL` contains full path without placeholders for SQLite driver.
- `APP_CACHE_DIR` identifies directory where you may find the SQLite database (`compatinfo-db.sqlite`) by default.
- `APP_HOME_DIR` identifies user home directory (whatever platform).

### Changed

- option `--version` display now only long version without application description.
- enhance how is displayed application version installed : Learn more on discussion [116](https://github.com/llaville/php-compatinfo-db/discussions/116)
- Checker service handle now, and print into diagnostic the application environment variables (keys/values).
- Launch an auto diagnostic when a `db:*` command (excluding `db:create` and `db:init`) is run.
- `db:init` command use internally a command bus rather than a query bus (follow concept of CQRS architecture)
- `db:init` command add only instance from DistributionRepository
- `db:create` command separate presentation and handler as other db commands (follow concept of CQRS architecture)
- `db:create` command (only handle schema creation). Use `db:init` command to load database contents
- `db:list` command (always returns a platform and never create one on fly as previously in v3.x)
- Xdebug reference updated to version 3.1.3 (stable)

### Removed

- `Checker` service that was previously used to display `diagnose` command results (breaking layers architecture), replaced by `PrintDiagnose` trait.
- `PlatformRepository` now database does not contains anymore instance of current PHP Interpreter
- `--all` option of `db:list` command (becomes the default behaviour)

### Fixed

- [#112](https://github.com/llaville/php-compatinfo-db/issues/112) touch fails on read-only database (thanks to @remicollet for reporting)
- [#113](https://github.com/llaville/php-compatinfo-db/issues/113) `db:list` fails
- [#114](https://github.com/llaville/php-compatinfo-db/issues/114) `db:create` fails when database exists (thanks to @remicollet for reporting)
- display `manifest` on PHAR distribution. [Lear more](https://github.com/llaville/php-compatinfo-db/issues/111#issuecomment-1029708338)

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/4.4.0...HEAD
[4.4.0]: https://github.com/llaville/php-compatinfo-db/compare/4.3.0...4.4.0
[4.3.0]: https://github.com/llaville/php-compatinfo-db/compare/4.2.0...4.3.0
[4.2.0]: https://github.com/llaville/php-compatinfo-db/compare/4.1.0...4.2.0
[4.1.0]: https://github.com/llaville/php-compatinfo-db/compare/4.0.0...4.1.0
[4.0.0]: https://github.com/llaville/php-compatinfo-db/compare/3.18.0...4.0.0
