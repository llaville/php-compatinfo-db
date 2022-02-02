<!-- markdownlint-disable MD013 MD024 -->
# Changes in 4.x

All notable changes of the CompatInfoDB 4 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Added

- `about` command to display current long version and more information about this package.
- `APP_DATABASE_URL` contains full path without placeholders for SQLite driver.
- `APP_CACHE_DIR` identifies directory where you may find the SQLite database version (`compatinfo-db.sqlite`).
- `APP_HOME_DIR` identifies user home directory (whatever platform).

### Changed

- option `--version` display now only long version without application description.
- enhance how is displayed application version installed : Learn more on discussion [116](https://github.com/llaville/php-compatinfo-db/discussions/116)
- Checker service handle now, and print into diagnostic the application environment variables (keys/values).
- Launch an auto diagnostic when a `db:*` command (excluding `db:create` and `db:init`) is run.
- `db:init` command use internally a command bus rather than a query bus (follow concept of CQRS architecture)
- `db:create` command separate presentation and handler as other db commands (follow concept of CQRS architecture)
- `db:create` command (only handle schema creation). Use `db:init` command to load database contents
- `db:list` command (always returns a platform and never create one on fly as previously in v3.x)

### Removed

- `Checker` service that was previously used to display `diagnose` command results (breaking layers architecture), replaced by `PrintDiagnose` trait.
- `PlatformRepository` now database does not contains anymore instance of current PHP Interpreter

### Fixed

- [#112](https://github.com/llaville/php-compatinfo-db/issues/112) touch fails on read-only database (thanks to @remicollet for reporting)
- [#113](https://github.com/llaville/php-compatinfo-db/issues/113) `db:list` fails
- [#114](https://github.com/llaville/php-compatinfo-db/issues/114) `db:create` fails when database exists (thanks to @remicollet for reporting)

[unreleased]: https://github.com/llaville/php-compatinfo-db/compare/3.18.0...HEAD
