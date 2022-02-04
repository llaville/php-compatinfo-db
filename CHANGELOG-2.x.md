<!-- markdownlint-disable MD013 MD024 -->
# Changes in 2.x

All notable changes of the CompatInfoDB 2 release series will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [2.19.0] - 2020-10-03

### Added

- Support to PHP 7.2.34
- Support to PHP 7.3.23
- Support to PHP 7.4.11
- Start of PHP 8 support: backport commit 1f0c681 in master branch allows release 2.19 to run unit tests under PHP 8

### Changed

- Mailparse reference updated to version 3.1.1 (stable)
- OAuth reference updated to version 2.0.7 (stable)
- Xdebug reference updated to version 2.9.8 (stable)
- Zip reference updated to version 1.19.1 (stable)

## [2.18.0] - 2020-09-13

### Added

- Support to PHP 7.3.22
- Support to PHP 7.4.10

### Changed

- Ast reference updated to version 1.0.10 (stable)
- Igbinary reference updated to version 3.1.5 (stable)
- OAuth reference updated to version 2.0.6 (stable)
- Solr reference updated to version 2.5.1 (stable)

## [2.17.0] - 2020-08-15

### Added

- Support to PHP 7.2.33
- Support to PHP 7.3.21
- Support to PHP 7.4.9

### Changed

- Ast reference updated to version 1.0.8 (stable)
- Igbinary reference updated to version 3.1.4 (stable)
- Msgpack reference updated to version 2.1.1 (stable)

## [2.16.0] - 2020-07-11

### Added

- Support to PHP 7.2.32
- Support to PHP 7.3.20
- Support to PHP 7.4.8

### Changed

- Redis reference updated to version 5.3.1 (stable)
- Split ChangeLog files in release series 1 and 2, handle now with the [Git Semantic Versioning](https://github.com/markchalloner/git-semver/) tools.

### Removed

- removes `jean85/pretty-package-versions` constraint and add `composer/package-versions-deprecated` constraint for composer install strategy (and compatibility 1.x, 2.x)
- removes `bartlett/phpunit-loggertestlistener`, loggers dev dependencies and clean-up composer suggestions

## [2.15.0] - 2020-06-30

### Added

- Support to PHP 7.3.19
- Support to PHP 7.4.7
- Support to oci8 extension (version up to 2.2.0)

### Changed

- Xdebug reference updated to version 2.9.6 (stable)
- Zip reference updated to version 1.19.0 (stable)

## [2.14.0] - 2020-05-17

### Added

- Support to PHP 7.2.31
- Support to PHP 7.3.18
- Support to PHP 7.4.6

## [2.13.1] - 2020-05-11

### Changed

- [Outdated yac reference](https://github.com/llaville/php-compatinfo-db/issues/43). Thanks to @remicollet for his notification

## [2.13.0] - 2020-05-10

### Added

- Support to PHP 7.2.30
- Support to PHP 7.3.17
- Support to PHP 7.4.5

### Changed

- Amqp reference updated to version 1.10.0 (stable)
- Xdebug reference updated to version 2.9.5 (stable)
- Lzf reference updated to version 1.6.8 (stable)
- Redis reference updated to version 5.2.2 (stable)
- Yaml reference updated to version 2.1.0 (stable)
- remove Symfony 3.x compatibility and add support to Symfony 5.x
- remove `bartlett:db:backup` command
- make commands lazily loaded (see <https://symfony.com/doc/current/console/lazy_commands.html>)

## [2.12.0] - 2020-03-21

### Added

- Support to PHP 7.2.29
- Support to PHP 7.3.16
- Support to PHP 7.4.4

### Changed

- Zip reference updated to version 1.18.2 (stable)

## [2.11.0] - 2020-03-20

### Added

- Support to PHP 7.2.28
- Support to PHP 7.3.15
- Support to PHP 7.4.3

### Changed

- [GH-40](https://github.com/llaville/php-compatinfo-db/issues/40) zip reference (thanks to @remicollet)
- Zip reference updated to version 1.18.1 (stable)
- Msgpack reference updated to version 2.1.0 (stable)
- Xdebug reference updated to version 2.9.2 (stable)
- OAuth reference updated to version 2.0.5 (stable)
- SSH2 reference updated to version 1.2 (beta)
- Uploadprogress reference updated to version 1.1.3 (stable)
- Yaml reference updated to version 2.0.4 (stable)
- Redis reference updated to version 5.2.1 (stable)

## [2.10.0] - 2020-01-27

### Added

- Support to PHP 7.2.27
- Support to PHP 7.3.14
- Support to PHP 7.4.2

### Changed

- [GH-38](https://github.com/llaville/php-compatinfo-db/issues/38) redis reference (optional part)
- Igbinary reference updated to version 3.1.2 (stable)
- Svn reference updated to version 2.0.3 (beta)
- Uploadprogress reference updated to version 1.1.2 (stable)

## [2.9.0] - 2020-01-22

### Added

- Support to Redis 5

### Changed

- [GH-37](https://github.com/llaville/php-compatinfo-db/issues/37) opcache reference outdated information
- [GH-36](https://github.com/llaville/php-compatinfo-db/issues/36) varnish reference outdated information
- [GH-35](https://github.com/llaville/php-compatinfo-db/issues/35) redis reference outdated information
- [GH-34](https://github.com/llaville/php-compatinfo-db/issues/34) memcache reference outdated information
- Igbinary reference updated to version 3.1.1 (stable)
- Xdebug reference updated to version 2.9.1 (stable)

## [2.8.0] - 2019-12-30

### Added

- Support to PHP 7.2.26
- Support to PHP 7.3.13
- Support to PHP 7.4.1

### Changed

- Igbinary reference updated to version 3.1.0 (stable)
- Memcached reference updated to version 3.1.5 (stable)
- Xdebug reference updated to version 2.9.0 (stable)

## [2.7.0] - 2019-12-02

### Added

- Support to PHP 7.2.21, 7.2.22, 7.2.23, 7.2.24, 7.2.25
- Support to PHP 7.3.8, 7.3.9, 7.3.10, 7.3.11, 7.3.12
- Support to PHP 7.4

### Changed

- Solr reference updated to version 2.5.0 (stable) - See <https://github.com/llaville/php-compatinfo-db/issues/33>
- Xdebug reference updated to version 2.8.0 (stable)

## [2.6.0] - 2019-07-25

### Added

- Support to PHP 7.2.20
- Support to PHP 7.3.7

### Changed

- Added missing classes in Reflection reference (Fixed [issue](https://github.com/llaville/php-compat-info/issues/250))
- Mcrypt Reference was deprecated in PHP 7.1.0 and removed in PHP 7.2.0 (excluding pecl extension)
- Avoid to run unit tests on Mcrypt reference for PHP 7.2 or greater (for <https://pecl.php.net/package/mcrypt>)

## [2.5.0] - 2019-06-15

### Added

- Support to PHP 7.1.30
- Support to PHP 7.2.19
- Support to PHP 7.3.6

## [2.4.0] - 2019-05-18

### Added

- Support to PHP 7.1.29
- Support to PHP 7.2.18
- Support to PHP 7.3.5

### Changed

- Imagick reference updated to version 3.4.4 (stable)
- Xdebug reference updated to version 2.7.2 (stable)

## [2.3.0] - 2019-04-10

### Added

- Support to PHP 7.3.4
- Support to PHP 7.2.17
- Support to PHP 7.1.28

### Changed

- Igbinary reference updated to version 3.0.1 (stable)
- Mailparse reference updated to version 3.0.3 (stable)
- Redis reference updated to version 4.3.0 (stable)
- Xdebug reference updated to version 2.7.1 (stable)

## [2.2.0] - 2019-03-10

### Added

- Support to PHP 7.3.3
- Support to PHP 7.2.16
- Support to PHP 7.1.27

### Changed

- Xdebug reference updated to version 2.7.0 (stable)

## [2.1.1] - 2019-02-24

### Added

- Do not failed tests related to current installed extensions, when versions referenced are lowest

### Changed

- Uopz reference updated to version 6.0.1 (stable)

## [2.1.0] - 2019-02-13

### Added

- Support to PHP 7.3.2
- Support to PHP 7.2.15

## [2.0.0] - 2019-01-19

### Added

- Support to PHP 7.3.1
- Support to PHP 7.2.14
- Support to PHP 7.1.26
- Support to PHP 5.6.40

### Changed

- `bartlett:db:init` command allow to specify a final DB version to tag rather than temporary package version (2.x-dev@commit)
- `bartlett:db:init` command does not accept to build a specific extension anymore
- Amqp reference updated to version 1.9.4 (stable)
- Uopz reference updated to version 5.1.0 (stable)

## [2.0.0RC1] - 2018-12-31

### Added

- Support to PHP 7.3.0

### Changed

- Drop support to PHP 5
- APCu reference updated to version 5.1.16 (stable)
- Lzf reference updated to version 1.6.7 (stable)
- Memcached reference updated to version 3.1.3 (stable)
- Msgpack reference updated to version 2.0.3 (stable)

[2.19.0]: https://github.com/llaville/php-compatinfo-db/compare/2.18.0...2.19.0
[2.18.0]: https://github.com/llaville/php-compatinfo-db/compare/2.17.0...2.18.0
[2.17.0]: https://github.com/llaville/php-compatinfo-db/compare/2.16.0...2.17.0
[2.16.0]: https://github.com/llaville/php-compatinfo-db/compare/2.15.0...2.16.0
[2.15.0]: https://github.com/llaville/php-compatinfo-db/compare/2.14.0...2.15.0
[2.14.0]: https://github.com/llaville/php-compatinfo-db/compare/2.13.1...2.14.0
[2.13.1]: https://github.com/llaville/php-compatinfo-db/compare/2.13.0...2.13.1
[2.13.0]: https://github.com/llaville/php-compatinfo-db/compare/2.12.0...2.13.0
[2.12.0]: https://github.com/llaville/php-compatinfo-db/compare/2.11.0...2.12.0
[2.11.0]: https://github.com/llaville/php-compatinfo-db/compare/2.10.0...2.11.0
[2.10.0]: https://github.com/llaville/php-compatinfo-db/compare/2.9.0...2.10.0
[2.9.0]: https://github.com/llaville/php-compatinfo-db/compare/2.8.0...2.9.0
[2.8.0]: https://github.com/llaville/php-compatinfo-db/compare/2.7.0...2.8.0
[2.7.0]: https://github.com/llaville/php-compatinfo-db/compare/2.6.0...2.7.0
[2.6.0]: https://github.com/llaville/php-compatinfo-db/compare/2.5.0...2.6.0
[2.5.0]: https://github.com/llaville/php-compatinfo-db/compare/2.4.0...2.5.0
[2.4.0]: https://github.com/llaville/php-compatinfo-db/compare/2.3.0...2.4.0
[2.3.0]: https://github.com/llaville/php-compatinfo-db/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/llaville/php-compatinfo-db/compare/2.1.1...2.2.0
[2.1.1]: https://github.com/llaville/php-compatinfo-db/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/llaville/php-compatinfo-db/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/llaville/php-compatinfo-db/compare/2.0.0RC1...2.0.0
[2.0.0RC1]: https://github.com/llaville/php-compatinfo-db/compare/1.39.0...2.0.0RC1
