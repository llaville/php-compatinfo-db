<!-- markdownlint-disable MD013 MD024 -->
# Changes in 4.x

All notable changes of the CompatInfoDB 4 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

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

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/4.0.0...HEAD
[4.0.0]: https://github.com/llaville/php-compatinfo-db/compare/3.18.0...4.0.0
